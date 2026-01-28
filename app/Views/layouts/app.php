<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>iObras</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap via CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/iobras/public/">iObras</a>

    <div class="collapse navbar-collapse">
      <?php if (class_exists('Auth') && Auth::check()): ?>
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="/iobras/public/">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iobras/public/clientes">Clientes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iobras/public/contratos">Contratos</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="nav-link text-white">
              <?= htmlspecialchars(Auth::user()['name']) ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/iobras/public/logout">Sair</a>
          </li>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</nav>


<main class="container mt-4">
  <?= $__content ?>
</main>

</body>
</html>

