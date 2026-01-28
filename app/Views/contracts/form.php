<?php View::extends('layouts/app'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><?= htmlspecialchars($title) ?></h3>
  <a class="btn btn-outline-secondary" href="/iobras/public/contratos">Voltar</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="<?= htmlspecialchars($action) ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">

      <div class="mb-3">
        <label class="form-label">Cliente *</label>
        <select class="form-select" name="client_id" required>
          <option value="">Selecione...</option>
          <?php foreach ($clients as $cl): ?>
            <option value="<?= (int)$cl['id'] ?>"
              <?= !empty($contract) && (int)$contract['client_id'] === (int)$cl['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cl['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Nome da Obra *</label>
        <input class="form-control" name="obra_nome" required
               value="<?= htmlspecialchars($contract['obra_nome'] ?? '') ?>">
      </div>

      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Início</label>
          <input class="form-control" type="date" name="start_date"
                 value="<?= htmlspecialchars($contract['start_date'] ?? '') ?>">
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Fim</label>
          <input class="form-control" type="date" name="end_date"
                 value="<?= htmlspecialchars($contract['end_date'] ?? '') ?>">
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Valor</label>
          <input class="form-control" type="number" step="0.01" name="contract_value"
                 value="<?= htmlspecialchars($contract['contract_value'] ?? '0.00') ?>">
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Status</label>
          <?php $st = $contract['status'] ?? 'Ativo'; ?>
          <select class="form-select" name="status">
            <?php foreach (['Ativo','Pausado','Encerrado'] as $opt): ?>
              <option value="<?= $opt ?>" <?= $st === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <button class="btn btn-primary">Salvar</button>
    </form>
  </div>
</div>
