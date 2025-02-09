<?php

/**
 * @var Core\Router $router
 */

use App\Middlewares\Auth;
use App\Middlewares\CSRF;
use App\Middlewares\View;

$router->addGlobalMiddleware(View::class);
$router->addGlobalMiddleware(CSRF::class);
$router->addRouteMiddleware('auth', Auth::class);
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/posts', 'PostController@index');
$router->add('GET', '/posts/{id}', 'PostController@show');
$router->add('POST', '/posts/{id}/comments', 'CommentController@store', ['auth']);

$router->add('GET', '/login', 'AuthController@create');
$router->add('POST', '/login', 'AuthController@store');
$router->add('POST', '/logout', 'AuthController@destroy');

// ================== admin panel routes

$router->add('GET', '/admin/dashboard', 'Admin\DashboardController@index');


$router->add('GET', '/admin/posts', 'Admin\PostController@index');
$router->add('GET', '/admin/posts/create', 'Admin\PostController@create');
$router->add('POST', '/admin/posts', 'Admin\PostController@store');
$router->add('GET', '/admin/posts/{id}/edit', 'Admin\PostController@edit');
$router->add('POST', '/admin/posts/{id}', 'Admin\PostController@update');
$router->add('POST', '/admin/posts/{id}/delete', 'Admin\PostController@delete');
