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
require __DIR__ . '/../app/Models/Client.php';

require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/ClientsController.php';


Env::load(__DIR__ . '/../.env');
Auth::start();

// cria usuários seed automaticamente se tabela users estiver vazia
try { UserModel::seedIfEmpty(); } catch (Throwable $e) {}

$router = new Router();

$BASE = '/iobras/public';

$router->get($BASE .'/login', [AuthController::class, 'loginForm']);
$router->post($BASE .'/login', [AuthController::class, 'login']);
$router->get($BASE .'/logout', [AuthController::class, 'logout']);

$router->get($BASE . '/clientes', [ClientsController::class, 'index']);
$router->get($BASE . '/clientes/novo', [ClientsController::class, 'createForm']);
$router->post($BASE . '/clientes/novo', [ClientsController::class, 'create']);
$router->get($BASE . '/clientes/editar', [ClientsController::class, 'editForm']);
$router->post($BASE . '/clientes/editar', [ClientsController::class, 'update']);
$router->post($BASE . '/clientes/excluir', [ClientsController::class, 'delete']);


$router->get($BASE . '/', function() {
  AuthMiddleware::requireLogin();
  View::render('home');
});

$router->dispatch();