<?php

// A sessão já foi iniciada pelo router em index.php
if (!isset($_SESSION['erro'])) {
    $_SESSION['erro'] = [];
}

require __DIR__ . "/../models/UserModel.php";
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../security/UseSecurity.php';
require __DIR__ . '/../middleware/UseMiddleware.php';
require __DIR__ . '/../helpers/UseHelpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    // =================================================
    // CADASTRAR
    // =================================================
    if ($acao === 'cadastrar') {

        $nome     = sanitizar($_POST['nome'] ?? '', 'nome');
        $email    = sanitizar($_POST['email'] ?? '', 'email');
        $senha    = $_POST['senha'] ?? '';
        $confirma = $_POST['confirmar_senha'] ?? '';

        validarSenha($senha);
        confirmarSenha($senha, $confirma);
        validarEmail($email);

        if (empty($_SESSION['erro'])) {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            cadastrarUsuario($pdo, $nome, $email, $senha_hash);

            // CORREÇÃO: Redireciona para a rota raiz amigável
            header("Location: /");
            exit();
        } else {
            // Se houver erro, volta para a tela de cadastro
            header("Location: /cadastrar");
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
            // CORREÇÃO: Redireciona para a rota amigável após o login
            header("Location: .");
            exit();
        } else {
            // Se der erro no login, recarrega a página de login para exibir a mensagem
            header("Location: login");
            exit();
        }
    }
}
?>