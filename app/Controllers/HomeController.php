<?php

namespace App\Controllers;

use Core\View;
use App\Models\User;

class HomeController
{

  public function index()

  {
    User::create([
      'name' => 'Ivan',
      'email' => 'nikcevici13@gmail.com',
      'role' => 'admin',
      'password' => password_hash("admin123", PASSWORD_DEFAULT)
    ]);
    User::create([
      'name' => 'John',
      'email' => 'john13@gmail.com',
      'role' => 'admin',
      'password' => password_hash("john123", PASSWORD_DEFAULT)
    ]);
    return View::render(template: "home/index", data: ['message' => "Hello!"], layout: 'layouts/main');
  }
}
