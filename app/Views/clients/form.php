<?php View::extends('layouts/app'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><?= htmlspecialchars($title) ?></h3>
  <a class="btn btn-outline-secondary" href="/iobras/public/clientes">Voltar</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="<?= htmlspecialchars($action) ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">

      <div class="mb-3">
        <label class="form-label">Nome *</label>
        <input class="form-control" name="name" required
               value="<?= htmlspecialchars($client['name'] ?? '') ?>">
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Documento</label>
          <input class="form-control" name="document"
                 value="<?= htmlspecialchars($client['document'] ?? '') ?>">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" type="email"
                 value="<?= htmlspecialchars($client['email'] ?? '') ?>">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Telefone</label>
          <input class="form-control" name="phone"
                 value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
        </div>
      </div>

      <button class="btn btn-primary">Salvar</button>
    </form>
  </div>
</div>
