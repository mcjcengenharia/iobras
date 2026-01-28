<?php

class ContractsController
{
    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $contracts = ContractModel::all();
        View::render('contracts/index', ['contracts' => $contracts]);
    }

    public function createForm(): void
    {
        AuthMiddleware::requireLogin();
        $clients = ClientModel::all();

        View::render('contracts/form', [
            'csrf' => Csrf::token(),
            'contract' => null,
            'clients' => $clients,
            'action' => '/iobras/public/contratos/novo',
            'title' => 'Novo Contrato/Obra'
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        ContractModel::create($_POST);
        header('Location: /iobras/public/contratos');
        exit;
    }

    public function editForm(): void
    {
        AuthMiddleware::requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $contract = ContractModel::find($id);
        if (!$contract) {
            http_response_code(404);
            exit('Contrato não encontrado');
        }

        $clients = ClientModel::all();

        View::render('contracts/form', [
            'csrf' => Csrf::token(),
            'contract' => $contract,
            'clients' => $clients,
            'action' => '/iobras/public/contratos/editar?id=' . $id,
            'title' => 'Editar Contrato/Obra'
        ]);
    }

    public function update(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        $id = (int)($_GET['id'] ?? 0);
        ContractModel::update($id, $_POST);

        header('Location: /iobras/public/contratos');
        exit;
    }

    public function delete(): void
    {
        AuthMiddleware::requireLogin();
        Csrf::check();

        $id = (int)($_POST['id'] ?? 0);
        ContractModel::delete($id);

        header('Location: /iobras/public/contratos');
        exit;
    }
}
