<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../src/routes/CreateRouter.php';

$router = new CreateRouter();

// 1. Rota Principal (Home)
$router->get('/', function () {
    require '../src/views/home.php';
});

// 2. Rotas de Login
$router->get('/login', function () {
    require '../src/views/login.php';
});

$router->post('/login', function () {
    // CORRIGIDO: Nome do arquivo é UserControll.php
    require __DIR__ . '/../../back/controllers/UserControll.php';
});

// 3. Rotas de Cadastro (ADICIONADAS)
$router->get('/cadastrar', function () {
    require '../src/views/cadastrar.php';
});

$router->post('/cadastrar', function () {
    // CORRIGIDO: Processa o cadastro apontando para o controller correto
    require __DIR__ . '/../../back/controllers/UserControll.php';
});

// 4. Painel do Banco
$router->get('/database/configuracao', function () {
    require '../../database/index.php';
});

$router->dispatch();

?>