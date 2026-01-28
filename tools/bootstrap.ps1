# tools/bootstrap.ps1
$ErrorActionPreference = "Stop"

function Ensure-Dir($p) { if (!(Test-Path $p)) { New-Item -ItemType Directory -Force -Path $p | Out-Null } }
function Write-File($path, $content) {
  Ensure-Dir (Split-Path $path -Parent)
  Set-Content -Path $path -Value $content -Encoding UTF8
}

# Estrutura
Ensure-Dir "app/Controllers"
Ensure-Dir "app/Models"
Ensure-Dir "app/Services"
Ensure-Dir "app/Helpers"
Ensure-Dir "app/Middleware"
Ensure-Dir "app/Core"
Ensure-Dir "app/Views/layouts"
Ensure-Dir "public/assets/js"
Ensure-Dir "database"
Ensure-Dir "tools"

# .gitignore
Write-File ".gitignore" @"
.env
/vendor
/node_modules
.DS_Store
*.log
"@

# env example
Write-File ".env.example" @"
APP_ENV=local
APP_URL=http://localhost

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prd_system
DB_USERNAME=root
DB_PASSWORD=
"@

# Apache rewrite
Write-File "public/.htaccess" @"
Options -Indexes
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
"@

# README
Write-File "README.md" @"
# iObras (PRD) — Módulos 1–7

## Requisitos
- PHP 8.1
- MySQL 8
- Apache (mod_rewrite habilitado)

## Instalação
1) Copie `.env.example` -> `.env` e ajuste credenciais
2) Crie o banco `prd_system`
3) Rode `database/install.sql`
4) Apache: DocumentRoot apontando para `public/` e `AllowOverride All`
5) Acesse `/`

## Estrutura
- app/ (Core, Controllers, Models, Services, Views)
- public/ (index.php, assets)
- database/ (install.sql)
"@

# Core: Env loader
Write-File "app/Core/Env.php" @"
<?php
class Env {
  public static function load(string \$path): void {
    if (!file_exists(\$path)) return;
    \$lines = file(\$path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (\$lines as \$line) {
      \$line = trim(\$line);
      if (\$line === '' || str_starts_with(\$line, '#')) continue;
      \$parts = explode('=', \$line, 2);
      if (count(\$parts) !== 2) continue;
      \$k = trim(\$parts[0]);
      \$v = trim(\$parts[1]);
      \$v = trim(\$v, '\"');
      \$_ENV[\$k] = \$v;
      putenv(\"\$k=\$v\");
    }
  }
  public static function get(string \$key, \$default=null) {
    return \$_ENV[\$key] ?? getenv(\$key) ?? \$default;
  }
}
"@

# Core: DB
Write-File "app/Core/DB.php" @"
<?php
class DB {
  private static ?PDO \$pdo = null;

  public static function conn(): PDO {
    if (self::\$pdo) return self::\$pdo;

    \$host = Env::get('DB_HOST','127.0.0.1');
    \$port = Env::get('DB_PORT','3306');
    \$db   = Env::get('DB_DATABASE','prd_system');
    \$user = Env::get('DB_USERNAME','root');
    \$pass = Env::get('DB_PASSWORD','');

    \$dsn = \"mysql:host=\$host;port=\$port;dbname=\$db;charset=utf8mb4\";
    self::\$pdo = new PDO(\$dsn, \$user, \$pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return self::\$pdo;
  }
}
"@

# Core: Router
Write-File "app/Core/Router.php" @"
<?php
class Router {
  private array \$routes = ['GET'=>[], 'POST'=>[]];

  public function get(string \$path, \$handler): void { \$this->routes['GET'][\$path] = \$handler; }
  public function post(string \$path, \$handler): void { \$this->routes['POST'][\$path] = \$handler; }

  public function dispatch(): void {
    \$method = \$_SERVER['REQUEST_METHOD'] ?? 'GET';
    \$uri = parse_url(\$_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    \$uri = rtrim(\$uri, '/') ?: '/';

    \$handler = \$this->routes[\$method][\$uri] ?? null;
    if (!\$handler) { http_response_code(404); echo \"404\"; return; }

    if (is_array(\$handler)) {
      \$class = \$handler[0]; \$fn = \$handler[1];
      \$obj = new \$class();
      \$obj->\$fn();
      return;
    }
    call_user_func(\$handler);
  }
}
"@

# View helper simples
Write-File "app/Core/View.php" @"
<?php
class View {
  private static ?string \$layout = null;
  private static array \$sections = [];

  public static function extends(string \$layout): void { self::\$layout = \$layout; }
  public static function render(string \$view, array \$data=[]): void {
    extract(\$data);
    self::\$layout = null;

    \$viewPath = __DIR__ . '/../Views/' . \$view . '.php';
    if (!file_exists(\$viewPath)) { throw new Exception('View não encontrada: ' . \$view); }

    ob_start();
    include \$viewPath;
    \$content = ob_get_clean();

    if (self::\$layout) {
      \$layoutPath = __DIR__ . '/../Views/' . self::\$layout . '.php';
      if (!file_exists(\$layoutPath)) { throw new Exception('Layout não encontrado: ' . self::\$layout); }
      \$__content = \$content;
      include \$layoutPath;
      return;
    }

    echo \$content;
  }
}
"@

# Layout base
Write-File "app/Views/layouts/app.php" @"
<!doctype html>
<html lang=\"pt-br\">
<head>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <title>iObras</title>
  <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
</head>
<body class=\"bg-light\">
  <nav class=\"navbar navbar-expand-lg navbar-dark bg-dark\">
    <div class=\"container\">
      <a class=\"navbar-brand\" href=\"/\">iObras</a>
      <div class=\"navbar-nav\">
        <a class=\"nav-link\" href=\"/reports\">Relatórios</a>
      </div>
    </div>
  </nav>

  <main class=\"container py-4\">
    <?= \$__content ?>
  </main>

  <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js\"></script>
</body>
</html>
"@

# Página inicial
Write-File "app/Views/home.php" @"
<?php View::extends('layouts/app'); ?>
<div class=\"p-4 bg-white rounded shadow-sm\">
  <h3>iObras (PRD)</h3>
  <p>Base instalada. Próximo commit injeta módulos 1–7 e rotas.</p>
</div>
"@

# public/index.php
Write-File "public/index.php" @"
<?php
require __DIR__ . '/../app/Core/Env.php';
require __DIR__ . '/../app/Core/DB.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/View.php';

Env::load(__DIR__ . '/../.env');

\$router = new Router();

// Rotas mínimas (vamos expandir no próximo commit)
\$router->get('/', function() { View::render('home'); });

\$router->dispatch();
"@

# database/install.sql (placeholder — vamos completar no próximo commit)
Write-File "database/install.sql" @"
-- install.sql (placeholder)
-- No próximo commit entra o schema completo + views (módulos 1–7)
"@

Write-Host "OK: estrutura base criada."

