<?php

class ClientsController
{
    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $clients = ClientModel::all();
        View::render('clients/index', ['clients' => $clients]);
    }

    public function createForm(): void
    {
        AuthMiddleware::requireLogin();
        View::render('clients/form', [
            'csrf' => Csrf::token(),
            'client' => null,
            'action' => '/iobras/public/clientes/novo',
            'title' => 'Novo Cliente'
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        ClientModel::create($_POST);
        header('Location: /iobras/public/clientes');
        exit;
    }

    public function editForm(): void
    {
        AuthMiddleware::requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $client = ClientModel::find($id);

        if (!$client) {
            http_response_code(404);
            exit('Cliente não encontrado');
        }

        View::render('clients/form', [
            'csrf' => Csrf::token(),
            'client' => $client,
            'action' => '/iobras/public/clientes/editar?id=' . $id,
            'title' => 'Editar Cliente'
        ]);
    }

    public function update(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        $id = (int)($_GET['id'] ?? 0);
        ClientModel::update($id, $_POST);

        header('Location: /iobras/public/clientes');
        exit;
    }

    public function delete(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        $id = (int)($_POST['id'] ?? 0);
        ClientModel::delete($id);

        header('Location: /iobras/public/clientes');
        exit;
    }
}
