<?php

namespace App\Models;

use Core\Model;
use Core\App;

class User extends Model
{
  protected static $table = 'users';

  public $id;
  public $email;
  public $name;
  public $password;
  public $role;
  public $created_at;

  public static function findByEmail(string $email): ?User
  {
    $db = App::get('database');
    $result = $db->fetch("SELECT * FROM " . static::$table . " WHERE email = ?", [$email], static::class);
    return $result ? $result : null;
  }
}
