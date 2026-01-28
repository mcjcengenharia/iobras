<?php View::extends('layouts/app'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Contratos / Obras</h3>
  <a class="btn btn-primary" href="/iobras/public/contratos/novo">Novo Contrato</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <?php if (empty($contracts)): ?>
      <div class="text-muted">Nenhum contrato cadastrado.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Obra</th>
              <th>Cliente</th>
              <th>Início</th>
              <th>Fim</th>
              <th>Valor</th>
              <th>Status</th>
              <th style="width:180px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($contracts as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['obra_nome']) ?></td>
                <td><?= htmlspecialchars($c['client_name']) ?></td>
                <td><?= htmlspecialchars($c['start_date'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['end_date'] ?? '') ?></td>
                <td>R$ <?= number_format((float)$c['contract_value'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-secondary" href="/iobras/public/contratos/editar?id=<?= (int)$c['id'] ?>">Editar</a>

                  <form method="post" action="/iobras/public/contratos/excluir" class="d-inline"
                        onsubmit="return confirm('Excluir este contrato?');">
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
