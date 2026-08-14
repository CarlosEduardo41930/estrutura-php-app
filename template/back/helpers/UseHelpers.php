<?php


function traduz_data_para_exibir($data)
{
    if ($data == "" or $data == "0000-00-00") {
        return "";
    }
    $dados = explode("-", $data);
    $data_exibir = "{$dados[2]}/{$dados[1]}/{$dados[0]}";
    return $data_exibir;
}


function formatar_moeda($valor)
{
    return "R$ " . number_format($valor, 2, ',', '.');
}

function resumir_texto($texto, $limite = 100)
{
    if (mb_strlen($texto) <= $limite) {
        return $texto;
    }
    return mb_substr($texto, 0, $limite) . '...';
}

function pegar_erro()
{
    $erros = $_SESSION['erro'] ?? [];
    unset($_SESSION['erro']);
    return $erros;
}

?>