<?php

namespace Core;

use PDO;
use PDOException;
use Exception;
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
      throw new Exception("Error: Database connection failed");
    }
  }

  protected function createDsn(array $config): string
  {
    $driver = $config['driver'];
    $dbname = $config['dbname'];

    return match ($driver) {
      'sqlite' => "sqlite:$dbname",
      default => throw new Exception("Error: Database driver not found: $driver")
    };
  }

  public function query(string $sql, array $params = []): PDOStatement
  {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
  }

  public function fetchAll(string $sql, array $params = []): array
  {
    $stmt = $this->query($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function fetch(string $sql, array $params = []): object|false
  {
    return $this->query($sql, $params)->fetch(PDO::FETCH_OBJ);
  }

  public function lastInsertId(): string|false
  {
    return $this->pdo->lastInsertId();
  }
}
