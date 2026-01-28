<?php
require __DIR__ . '/../app/Core/Env.php';
require __DIR__ . '/../app/Core/DB.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/View.php';

Env::load(__DIR__ . '/../.env');

\ = new Router();

// Rotas mÃ­nimas (vamos expandir no prÃ³ximo commit)
\->get('/', function() { View::render('home'); });

\->dispatch();
