<?php View::extends('layouts/app'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Clientes</h3>
  <a class="btn btn-primary" href="/iobras/public/clientes/novo">Novo Cliente</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <?php if (empty($clients)): ?>
      <div class="text-muted">Nenhum cliente cadastrado.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Documento</th>
              <th>Email</th>
              <th>Telefone</th>
              <th style="width:180px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clients as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= htmlspecialchars($c['document'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-secondary" href="/iobras/public/clientes/editar?id=<?= (int)$c['id'] ?>">Editar</a>

                  <form method="post" action="/iobras/public/clientes/excluir" class="d-inline"
                        onsubmit="return confirm('Excluir este cliente?');">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
