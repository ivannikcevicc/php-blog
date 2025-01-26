<?php

namespace Core;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
  protected $pdo;

  public function __construct(array $config)
  {
    try {
      $dsn = $this->createDsn($config);
      $username = $config['username'] ?? null;
      $password = $config['password'] ?? null;
      $options = $config['options'] ?? null;
      $this->pdo = new PDO($dsn, $username, $password, $options);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      throw new Exception("Could not connect to the database");
    }
  }

  protected function createDsn(array $config): string
  {
    $driver = $config['driver'];
    $dbname = $config['dbname'];
    return match ($driver) {
      'sqlite' => "sqlite:$dbname",
      default => throw new Exception("Unsupported database driver: $driver")
    };
  }

  public function query(string $sql, array $params = []): PDOStatement
  {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  public function fetchAll(string $sql, array $params = [], ?string $className = null): array
  {
    $stmt = $this->query($sql, $params);
    if ($className) {
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return array_map(function ($row) use ($className) {
        $object = new $className();
        foreach ($row as $key => $value) {
          if (property_exists($object, $key)) {
            $object->$key = $value;
          }
        }
        return $object;
      }, $results);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function fetch(string $sql, array $params = [], ?string $className = null): mixed
  {
    $stmt = $this->query($sql, $params);
    if ($className) {
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result) {
        $object = new $className();
        foreach ($result as $key => $value) {
          if (property_exists($object, $key)) {
            $object->$key = $value;
          }
        }
        return $object;
      }
      return null;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function lastInsertId(): string|false
  {
    return $this->pdo->lastInsertId();
  }
}
