<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION['erro'] = [];
}

require __DIR__ . "/../models/UserModel.php";
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../security/UseSecurity.php';
require __DIR__ . '/../middleware/UseMiddleware.php';
require __DIR__ . '/../helpers/UseHelpers.php';

/*
 * Exemplo: processar formulário de cadastro
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    // =================================================
    // CADASTRAR
    // =================================================

    if ($acao === 'cadastrar') {

        $nome  = sanitizar($_POST['nome'] ?? '', 'nome');
        $email = sanitizar($_POST['email'] ?? '', 'email');
        $senha = $_POST['senha'] ?? '';
        $confirma = $_POST['confirmar_senha'] ?? '';

        validarSenha($senha);
        confirmarSenha($senha, $confirma);
        validarEmail($email);

        if (empty($_SESSION['erro'])) {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            cadastrarUsuario($pdo, $nome, $email, $senha_hash);

            header("Location: ../../front/public/index.php");
            exit();
        }
    }

    // =================================================
    // LOGIN
    // =================================================

    if ($acao === 'login') {

        $email = sanitizar($_POST['email'] ?? '', 'email');
        $senha = $_POST['senha'] ?? '';

        validarLogin($pdo, $senha, $email);

        if (empty($_SESSION['erro'])) {
            header("Location: ../../front/public/index.php");
            exit();
        }
    }
}

?>