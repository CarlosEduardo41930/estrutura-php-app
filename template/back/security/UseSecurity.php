<?php


function sanitizar(string $dado, string $tipo = 'texto'): string
{
    $dado = trim($dado);
    $dado = preg_replace('/[\x00-\x1F\x7F]/u', '', $dado);
    $dado = strip_tags($dado);
    switch ($tipo) {

        case 'nome':
            // Permite apenas letras (incluindo acentos), espaços e hífens
            $dado = preg_replace('/[^a-zA-ZÀ-ÿ\s\-]/u', '', $dado);
            // Colapsa espaços múltiplos em um só
            $dado = preg_replace('/\s+/', ' ', $dado);
            break;

        case 'email':
            // Remove tudo que não faz parte de um e-mail
            $dado = filter_var($dado, FILTER_SANITIZE_EMAIL);
            break;

        case 'inteiro':
            // Mantém apenas dígitos e sinal negativo
            $dado = preg_replace('/[^0-9\-]/', '', $dado);
            break;

        case 'texto':
        default:
            // Já passou pelas etapas
            break;
    }
    if ($tipo === 'nome') {
        $tamanho = mb_strlen($dado, 'UTF-8');

        if ($tamanho >= 2 && $tamanho <= 50) {
        } else {
            $_SESSION['erro'][] =  "Nome: Nome deve ter entre 2 e 50 caracteres.";
        }
    }

    return $dado;
}


function validarSenha(string $senha): void
{

    if (strlen($senha) < 6) {
        $_SESSION['erro'][] =  "Senha: Mínimo 6 caracteres";
    }

    if (!preg_match('/[A-Z]/', $senha)) {
        $_SESSION['erro'][] =  "Senha: Pelo menos uma letra maiúscula";
    }


    if (!preg_match('/[0-9]/', $senha)) {
        $_SESSION['erro'][] =  "Senha: Pelo menos um número";
    }
}
function confirmarSenha(string $senha, string $confirmar_senha)
{

    if ($senha !== $confirmar_senha) {
        $_SESSION['erro'][] = 'As senhas não coincidem.';
    }
}



function validarCPF($cpf)
{



    // Remove tudo que não for número
    try {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) != 11) {
            //throw new Exception("CPF deve conter 11 dígitos");
            $_SESSION['erro'][] = "CPF deve conter 11 dígitos";
        }

        // Elimina CPFs inválidos (todos iguais)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            //throw new Exception("CPF inválido");
            $_SESSION['erro'][] = "CPF inválido";
        }

        // Primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }

        $dig1 = ($soma * 10) % 11;
        if ($dig1 == 10) {
            $dig1 = 0;
        }

        // Segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }

        $dig2 = ($soma * 10) % 11;
        if ($dig2 == 10) {
            $dig2 = 0;
        }


        // Verifica se os dígitos batem

        if ($cpf[9] == $dig1 && $cpf[10] == $dig2) {
            return true;
        } else {
            //throw new Exception("CPF inválido");
            $_SESSION['erro'][] = "CPF inválido";
        }
    } catch (Exception $e) {
        $_SESSION['erro'][] = "Erro ao cadastrar: " . $e->getMessage();
    }
}