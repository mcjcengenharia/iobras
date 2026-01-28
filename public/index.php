<?php
require __DIR__ . '/../app/Core/Env.php';
require __DIR__ . '/../app/Core/DB.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/View.php';
require __DIR__ . '/../app/Core/Auth.php';

require __DIR__ . '/../app/Helpers/Csrf.php';

require __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require __DIR__ . '/../app/Middleware/RoleMiddleware.php';

require __DIR__ . '/../app/Models/User.php';
require __DIR__ . '/../app/Controllers/AuthController.php';

Env::load(__DIR__ . '/../.env');
Auth::start();

// cria usuários seed automaticamente se tabela users estiver vazia
try { UserModel::seedIfEmpty(); } catch (Throwable $e) {}

$router = new Router();

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/', function() {
  AuthMiddleware::requireLogin();
  View::render('home');
});

$router->dispatch();