<?php
global $BASE;
require_once '../src/components/useComponents.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $BASE ?>/">
    <title>Cadastrar — Meu Projeto</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            width: 420px;
            max-width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
        }

        .card h1 {
            text-align: center;
            color: #333;
            margin: 0 0 25px;
            font-size: 24px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
            outline: none;
        }

        input:focus {
            border-color: #2a5298;
        }

        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 6px;
            background: #2a5298;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #1e3c72;
        }

        .voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2a5298;
            font-size: 13px;
            text-decoration: none;
        }

        .voltar:hover {
            text-decoration: underline;
        }

        .msg-erro {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 14px;
            margin-bottom: 12px;
            border-left: 4px solid #c0392b;
            border-radius: 4px;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="card">

        <h1>Cadastrar</h1>

        <?php mostrarErros(); ?>

        <form method="POST" action="/cadastrar">

            <label for="nome">Nome</label>
            <input
                type="text"
                id="nome"
                name="nome"
                required
                placeholder="Seu nome"
            >

            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                placeholder="seu@email.com"
            >

            <label for="senha">Senha</label>
            <input
                type="password"
                id="senha"
                name="senha"
                required
                placeholder="Mínimo 6 caracteres"
            >

            <label for="confirmar_senha">Confirmar Senha</label>
            <input
                type="password"
                id="confirmar_senha"
                name="confirmar_senha"
                required
                placeholder="Repita a senha"
            >

            <input type="hidden" name="acao" value="cadastrar">

            <button type="submit">Cadastrar</button>

        </form>

        <a href="/login" class="voltar">Já tem conta? Entrar</a>

    </div>

</body>
</html>