<?php

require_once '../src/routes/CreateRouter.php';

$router = new CreateRouter();

$router->get('/', function () {
    require '../src/views/home.php';
});

$router->get('/login', function () {
    require '../src/views/login.php';
});

$router->post('/login', function () {
    require __DIR__ . '/../../back/controllers/UseControll.php';
});

$router->get('/database/configuracao', function () {
    require '../../database/index.php';
});

$router->dispatch();

?>