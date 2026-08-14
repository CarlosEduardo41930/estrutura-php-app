<?php
global $BASE; // Add esta linha para resolver o aviso do VS Code
require_once '../src/components/useComponents.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $BASE ?>/">
    <title>Home — Meu Projeto</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        /* ============================
           NAVBAR
           ============================ */

        .navbar {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 14px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: #2a5298;
            font-weight: bold;
            font-size: 18px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: normal;
            color: #555;
        }

        .nav-links a:hover {
            color: #2a5298;
        }

        /* ============================
           CONTEÚDO
           ============================ */

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: #fff;
            padding: 24px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 5px solid #2a5298;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .card h1 {
            margin: 0 0 8px;
            color: #333;
            font-size: 26px;
        }

        .card h2 {
            margin: 0 0 10px;
            color: #333;
            font-size: 20px;
        }

        .card p {
            color: #666;
            font-size: 14px;
            line-height: 1.7;
            margin: 0 0 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 22px;
            background: #2a5298;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #1e3c72;
        }

        /* ============================
           TABELA
           ============================ */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* ============================
           MENSAGENS DE ERRO
           ============================ */

        .msg-erro {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 14px;
            margin-bottom: 8px;
            border-left: 4px solid #c0392b;
            border-radius: 4px;
            font-size: 14px;
        }

        /* ============================
           RODAPÉ
           ============================ */

        .rodape {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 12px;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <a href=".">Meu Projeto</a>
        <div class="nav-links">
            <a href=".">Início</a>
            <a href="login">Entrar</a>
            <a href="database/configuracao">Banco</a>
        </div>
    </div>

    <div class="container">

        <!-- ERROS -->
        <?php mostrarErros(); ?>

        <!-- BOAS-VINDAS -->
        <div class="card">
            <h1>Bem-vindo ao Projeto Base</h1>
            <p>
                Este é o ponto de partida para seus projetos PHP.
                Aqui você já tem a estrutura de pastas, rotas,
                segurança, helpers e painel do banco configurados.
            </p>
            <p>
                Acesse o painel do banco para criar as tabelas
                antes de começar a desenvolver.
            </p>
            <a href="/database/configuracao" class="btn">
                Abrir Painel do Banco
            </a>
        </div>

        <!-- ESTRUTURA -->
        <div class="card">
            <h2>Estrutura do Projeto</h2>
            <p>
                <strong>back/</strong> — Controllers, Models, Security, Middleware, Helpers, Services<br>
                <strong>front/</strong> — Views, Components, Routes, Styles, Scripts<br>
                <strong>database/</strong> — Config do banco, SQL, Painel de gerenciamento
            </p>
        </div>

        <!-- TABELA EXEMPLO -->
        <div class="card">
            <h2>Usuários de Exemplo</h2>
            <?php mostrarTabela(); ?>
        </div>

    </div>

    <!-- RODAPÉ -->
    <div class="rodape">
        Meu Projeto &copy; <?php echo date('Y'); ?>
    </div>

    <script src="../src/scripts/scripts.js"></script>
</body>
</html>