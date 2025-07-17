<?php
use App\Core\Router;

// Asegúrate de que $db esté definido (debería venir de tu archivo de configuración)
$router = new Router($db);

// Rutas públicas
$router->addRoute('GET', '/', 'App\Controllers\HomeController', 'index');
$router->addRoute(['GET', 'POST'], '/login', 'App\Controllers\AuthController', 'handleLogin');
$router->addRoute(['GET', 'POST'], '/register', 'App\Controllers\AuthController', 'handleRegister');
$router->addRoute(['GET', 'POST'], '/forgot-password', 'App\Controllers\AuthController', 'handleForgotPassword');

// Rutas protegidas
$router->addRoute('GET', '/dashboard', 'App\Controllers\DashboardController', 'index', ['auth']);
$router->addRoute('GET', '/admin/dashboard', 'App\Controllers\Admin\DashboardController', 'index', ['auth' => ['role' => 'admin']]);
$router->addRoute('GET', '/librarian/dashboard', 'App\Controllers\Librarian\DashboardController', 'index', ['auth' => ['role' => 'librarian']]);

// Ruta con parámetros
$router->addRoute('GET', '/books/{id}', 'App\Controllers\BookController', 'show', ['auth']);

// Logout
$router->addRoute('GET', '/logout', 'App\Controllers\AuthController', 'logout');

return $router;