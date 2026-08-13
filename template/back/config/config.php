<?php

$host = "localhost";
$db   = "nome _do_banco";
$user = "nome_do_usuario";
$pass = "senha_do_banco";
$charset = "utf8mb4";

$conexao = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($conexao, $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

?>