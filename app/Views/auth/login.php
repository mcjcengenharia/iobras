<?php View::extends('layouts/app'); ?>

<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Login</h4>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/iobras/public/login">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" type="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input class="form-control" name="password" type="password" required>
          </div>

          <button class="btn btn-primary w-100">Entrar</button>
        </form>

        <hr>
        <small class="text-muted">
          Seed: admin@iobras.local / admin123
        </small>
      </div>
    </div>
  </div>
</div>
