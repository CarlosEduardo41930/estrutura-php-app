<?php

require_once '../src/routes/createRouter.php';

$router = new CreateRouter();

$router->get('/', function () {
    require '../src/views/home.php';
});

$router->get('/livros', function () {
    require '../src/views/listar.php';
});

// $router->get('/livros/cadastrar', function () {
//     require '../src/Views/livros/cadastrar.php';
// });

// $router->post('/livros/cadastrar', function () {
//     // Aqui você pode salvar o livro no banco
//     echo 'Livro cadastrado!';
// });

$router->dispatch();