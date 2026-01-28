<?php
//Require do Sistema
require __DIR__ . '/../app/Core/Env.php';
require __DIR__ . '/../app/Core/DB.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/View.php';
require __DIR__ . '/../app/Core/Auth.php';

//Require da Pasta Helpers
require __DIR__ . '/../app/Helpers/Csrf.php';

//Require da Pasta Middleware
require __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require __DIR__ . '/../app/Middleware/RoleMiddleware.php';

//Require da Pasta Models
require __DIR__ . '/../app/Models/User.php';
require __DIR__ . '/../app/Models/Client.php';
require __DIR__ . '/../app/Models/Contract.php';

//Require da Pasta Controllers
require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/ClientsController.php';
require __DIR__ . '/../app/Controllers/ContractsController.php';






Env::load(__DIR__ . '/../.env');
Auth::start();

// cria usuários seed automaticamente se tabela users estiver vazia
try { UserModel::seedIfEmpty(); } catch (Throwable $e) {}

$router = new Router();

$BASE = '/iobras/public';

//Rotas Login
$router->get($BASE .'/login', [AuthController::class, 'loginForm']);
$router->post($BASE .'/login', [AuthController::class, 'login']);
$router->get($BASE .'/logout', [AuthController::class, 'logout']);

//Rotas Clientes
$router->get($BASE . '/clientes', [ClientsController::class, 'index']);
$router->get($BASE . '/clientes/novo', [ClientsController::class, 'createForm']);
$router->post($BASE . '/clientes/novo', [ClientsController::class, 'create']);
$router->get($BASE . '/clientes/editar', [ClientsController::class, 'editForm']);
$router->post($BASE . '/clientes/editar', [ClientsController::class, 'update']);
$router->post($BASE . '/clientes/excluir', [ClientsController::class, 'delete']);

//Rotas Contratos
$router->get($BASE . '/contratos', [ContractsController::class, 'index']);
$router->get($BASE . '/contratos/novo', [ContractsController::class, 'createForm']);
$router->post($BASE . '/contratos/novo', [ContractsController::class, 'create']);
$router->get($BASE . '/contratos/editar', [ContractsController::class, 'editForm']);
$router->post($BASE . '/contratos/editar', [ContractsController::class, 'update']);
$router->post($BASE . '/contratos/excluir', [ContractsController::class, 'delete']);



$router->get($BASE . '/', function() {
  AuthMiddleware::requireLogin();
  View::render('home');
});

$router->dispatch();