<?php

require_once "config_banco_uso.php";

$mensagens = [];


// =====================================================
// LOCAL DO ARQUIVO SQL
// =====================================================

$arquivoSQL = __DIR__ . "/../back/banco/banco.sql";


// =====================================================
// FUNÇÃO PARA LER UMA SEÇÃO DO BANCO.SQL
// =====================================================

function pegarSecao($sql, $nomeSecao)
{
    $marcadorInicio = "-- [" . $nomeSecao . "]";

    $inicio = stripos($sql, $marcadorInicio);

    if ($inicio === false) {
        return false;
    }

    $inicio = $inicio + strlen($marcadorInicio);

    /*
     * Procura a próxima seção.
     *
     * Exemplo:
     *
     * [BANCO]
     * ...
     * [TABELAS]
     *
     */

    $proximaSecao = stripos($sql, "-- [", $inicio);

    if ($proximaSecao === false) {
        return trim(substr($sql, $inicio));
    }

    return trim(
        substr(
            $sql,
            $inicio,
            $proximaSecao - $inicio
        )
    );
}


// =====================================================
// EXECUTAR SQL
// =====================================================

function executarSQL($conn, $sql, &$mensagens)
{
    $sql = trim($sql);

    if ($sql === "") {

        $mensagens[] =
            "Nenhum comando SQL encontrado.";

        return false;
    }


    /*
     * Executa todos os comandos encontrados.
     */

    if (!$conn->multi_query($sql)) {

        $mensagens[] =
            "ERRO MYSQL: " .
            $conn->error;

        return false;
    }


    /*
     * Percorrer todos os resultados.
     */

    do {

        if ($resultado = $conn->store_result()) {

            $resultado->free();
        }

    } while (
        $conn->more_results() &&
        $conn->next_result()
    );


    /*
     * Se houve erro em algum comando.
     */

    if ($conn->errno) {

        $mensagens[] =
            "ERRO MYSQL: " .
            $conn->error;

        return false;
    }

    return true;
}


// =====================================================
// VERIFICAR BANCO.SQL
// =====================================================

if (!file_exists($arquivoSQL)) {

    $mensagens[] =
        "ERRO: o arquivo banco.sql não foi encontrado.";

} else {

    $sqlCompleto = file_get_contents($arquivoSQL);

    if ($sqlCompleto === false) {

        $mensagens[] =
            "ERRO: não foi possível ler o banco.sql.";

    } else {

        /*
         * Remove BOM caso o arquivo esteja salvo
         * como UTF-8 com BOM.
         */

        $sqlCompleto =
            preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $sqlCompleto
            );

        $mensagens[] =
            "banco.sql carregado com sucesso.";
    }
}


// =====================================================
// AÇÃO DO BOTÃO
// =====================================================

$acao = $_POST["acao"] ?? "";


// =====================================================
// EXECUTAR AÇÃO
// =====================================================

if (
    $acao !== "" &&
    isset($sqlCompleto)
) {


    // =================================================
    // CONEXÃO
    // =================================================

    $conn = new mysqli(
        $host,
        $user,
        $password
    );


    if ($conn->connect_error) {

        $mensagens[] =
            "Erro de conexão: " .
            $conn->connect_error;

    } else {

        $conn->set_charset("utf8mb4");

        $mensagens[] =
            "Conectado ao MySQL.";


        // =================================================
        // INSTALAR
        // =================================================

        if ($acao === "instalar") {

            $mensagens[] =
                "Iniciando instalação...";


            // ---------------------------------------------
            // CRIAR BANCO
            // ---------------------------------------------

            $sqlBanco =
                pegarSecao(
                    $sqlCompleto,
                    "BANCO"
                );


            if ($sqlBanco === false) {

                $mensagens[] =
                    "ERRO: seção [BANCO] não encontrada.";

            } else {

                if (
                    executarSQL(
                        $conn,
                        $sqlBanco,
                        $mensagens
                    )
                ) {

                    $mensagens[] =
                        "Banco criado/verificado.";
                }
            }


            // ---------------------------------------------
            // SELECIONAR BANCO
            // ---------------------------------------------

            if (
                $conn->select_db($banco)
            ) {

                $mensagens[] =
                    "Banco selecionado: " .
                    $banco;

            } else {

                $mensagens[] =
                    "ERRO ao selecionar banco: " .
                    $conn->error;
            }


            // ---------------------------------------------
            // CRIAR TABELAS
            // ---------------------------------------------

            $sqlTabelas =
                pegarSecao(
                    $sqlCompleto,
                    "TABELAS"
                );


            if ($sqlTabelas === false) {

                $mensagens[] =
                    "ERRO: seção [TABELAS] não encontrada.";

            } else {

                if (
                    executarSQL(
                        $conn,
                        $sqlTabelas,
                        $mensagens
                    )
                ) {

                    $mensagens[] =
                        "Tabelas criadas com sucesso.";
                }
            }


            $mensagens[] =
                "Instalação concluída.";
        }


        // =================================================
        // RECRIAR
        // =================================================

        elseif ($acao === "recriar") {

            $mensagens[] =
                "Iniciando recriação...";


            // Criar banco caso não exista

            $sqlBanco =
                pegarSecao(
                    $sqlCompleto,
                    "BANCO"
                );


            if ($sqlBanco !== false) {

                executarSQL(
                    $conn,
                    $sqlBanco,
                    $mensagens
                );
            }


            // Selecionar banco

            if (!$conn->select_db($banco)) {

                $mensagens[] =
                    "ERRO: " .
                    $conn->error;

            } else {

                $mensagens[] =
                    "Banco selecionado: " .
                    $banco;
            }


            // Desativar FK

            $conn->query(
                "SET FOREIGN_KEY_CHECKS = 0"
            );


            // Buscar tabelas

            $resultado =
                $conn->query(
                    "SHOW TABLES"
                );


            if ($resultado) {

                while (
                    $linha =
                    $resultado->fetch_array()
                ) {

                    $tabela =
                        $linha[0];

                    $tabelaSeguro =
                        "`" .
                        str_replace(
                            "`",
                            "``",
                            $tabela
                        ) .
                        "`";


                    if (
                        $conn->query(
                            "DROP TABLE IF EXISTS " .
                            $tabelaSeguro
                        )
                    ) {

                        $mensagens[] =
                            "Tabela excluída: " .
                            $tabela;

                    } else {

                        $mensagens[] =
                            "Erro ao excluir " .
                            $tabela .
                            ": " .
                            $conn->error;
                    }
                }

                $resultado->free();
            }


            // Recriar tabelas

            $sqlTabelas =
                pegarSecao(
                    $sqlCompleto,
                    "TABELAS"
                );


            if ($sqlTabelas !== false) {

                executarSQL(
                    $conn,
                    $sqlTabelas,
                    $mensagens
                );

                $mensagens[] =
                    "Tabelas recriadas.";
            }


            // Reativar FK

            $conn->query(
                "SET FOREIGN_KEY_CHECKS = 1"
            );


            $mensagens[] =
                "Recriação concluída.";
        }


        // =================================================
        // RESETAR
        // =================================================

        elseif ($acao === "resetar") {

            $mensagens[] =
                "Iniciando reset dos dados...";


            if (!$conn->select_db($banco)) {

                $mensagens[] =
                    "ERRO: " .
                    $conn->error;

            } else {

                $conn->query(
                    "SET FOREIGN_KEY_CHECKS = 0"
                );


                $resultado =
                    $conn->query(
                        "SHOW TABLES"
                    );


                if ($resultado) {

                    while (
                        $linha =
                        $resultado->fetch_array()
                    ) {

                        $tabela =
                            $linha[0];

                        $tabelaSeguro =
                            "`" .
                            str_replace(
                                "`",
                                "``",
                                $tabela
                            ) .
                            "`";


                        if (
                            $conn->query(
                                "TRUNCATE TABLE " .
                                $tabelaSeguro
                            )
                        ) {

                            $mensagens[] =
                                "Tabela limpa: " .
                                $tabela;

                        } else {

                            $mensagens[] =
                                "ERRO ao limpar " .
                                $tabela .
                                ": " .
                                $conn->error;
                        }
                    }

                    $resultado->free();
                }


                $conn->query(
                    "SET FOREIGN_KEY_CHECKS = 1"
                );


                $mensagens[] =
                    "Todos os dados foram removidos.";

                $mensagens[] =
                    "As tabelas foram mantidas.";
            }
        }


        // =================================================
        // INSERIR DADOS
        // =================================================

        elseif ($acao === "dados") {

            $mensagens[] =
                "Iniciando inserção dos dados...";


            // ---------------------------------------------
            // SELECIONAR BANCO
            // ---------------------------------------------

            if (!$conn->select_db($banco)) {

                $mensagens[] =
                    "ERRO ao selecionar banco: " .
                    $conn->error;

            } else {

                $mensagens[] =
                    "Banco selecionado: " .
                    $banco;


                // -----------------------------------------
                // PEGAR [DADOS]
                // -----------------------------------------

                $sqlDados =
                    pegarSecao(
                        $sqlCompleto,
                        "DADOS"
                    );


                if ($sqlDados === false) {

                    $mensagens[] =
                        "ERRO: a seção [DADOS] não foi encontrada.";

                } else {

                    /*
                     * Mostrar quantidade de caracteres.
                     * Isso ajuda a descobrir se o PHP
                     * realmente encontrou os INSERTs.
                     */

                    $quantidade =
                        strlen($sqlDados);

                    $mensagens[] =
                        "Seção [DADOS] encontrada.";

                    $mensagens[] =
                        "Tamanho dos dados SQL: " .
                        $quantidade .
                        " caracteres.";


                    if ($quantidade < 10) {

                        $mensagens[] =
                            "AVISO: a seção [DADOS] está vazia.";

                    } else {


                        // ---------------------------------
                        // EXECUTAR INSERTS
                        // ---------------------------------

                        if (
                            executarSQL(
                                $conn,
                                $sqlDados,
                                $mensagens
                            )
                        ) {

                            $mensagens[] =
                                "Dados inseridos com sucesso.";

                        } else {

                            $mensagens[] =
                                "Não foi possível inserir os dados.";
                        }
                    }
                }
            }
        }


        $conn->close();
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Painel do Banco</title>

<style>

*{
    box-sizing:border-box;
}

body{

    font-family:Arial, Helvetica, sans-serif;

    background:
    linear-gradient(
        135deg,
        #1e3c72,
        #2a5298
    );

    margin:0;

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:20px;
}

.container{

    background:white;

    width:500px;

    max-width:100%;

    padding:35px;

    border-radius:12px;

    box-shadow:
    0 10px 30px
    rgba(0,0,0,.25);
}

h1{

    text-align:center;

    color:#333;

    margin-top:0;
}

.banco{

    text-align:center;

    color:#666;

    margin-bottom:25px;
}

.card{

    background:#f7f7f7;

    padding:18px;

    margin-bottom:15px;

    border-radius:8px;

    border-left:5px solid #2a5298;
}

.card h2{

    margin:0 0 8px;

    font-size:18px;

}

.card p{

    color:#666;

    font-size:14px;

    margin-bottom:15px;
}

button{

    width:100%;

    padding:12px;

    border:0;

    border-radius:6px;

    background:#2a5298;

    color:white;

    cursor:pointer;

    font-size:15px;
}

button:hover{

    background:#1e3c72;
}

.recriar{

    background:#d9534f;
}

.recriar:hover{

    background:#b52b27;
}

.resetar{

    background:#f0ad4e;
}

.resetar:hover{

    background:#d58512;
}

.dados{

    background:#28a745;
}

.dados:hover{

    background:#1e7e34;
}

.status{

    border-top:1px solid #ddd;

    margin-top:25px;

    padding-top:20px;
}

.msg{

    background:#f5f5f5;

    padding:9px;

    margin-bottom:7px;

    border-left:4px solid #2a5298;

    border-radius:4px;

    font-size:14px;

}

</style>

</head>

<body>

<div class="container">

<h1>Painel do Banco</h1>

<div class="banco">

Banco:

<strong>
<?php echo htmlspecialchars($banco); ?>
</strong>

</div>


<div class="card">

<h2>Instalar Banco</h2>

<p>
Cria o banco e as tabelas usando o banco.sql.
</p>

<form method="POST" action="index.php">

<input
    type="hidden"
    name="acao"
    value="instalar"
>

<button type="submit">

Criar Banco

</button>

</form>

</div>


<div class="card">

<h2>Recriar Banco</h2>

<p>
Exclui todas as tabelas existentes e cria
novamente usando o banco.sql.
</p>

<form
    method="POST"
    onsubmit="
    return confirm(
        'ATENÇÃO! Todas as tabelas serão apagadas. Continuar?'
    );
    "
>

<input
    type="hidden"
    name="acao"
    value="recriar"
>

<button
    type="submit"
    class="recriar"
>

Recriar Banco

</button>

</form>

</div>


<div class="card">

<h2>Resetar Dados</h2>

<p>
Apaga todos os registros mantendo as tabelas.
</p>

<form
    method="POST"
    onsubmit="
    return confirm(
        'ATENÇÃO! Todos os dados serão apagados. Continuar?'
    );
    "
>

<input
    type="hidden"
    name="acao"
    value="resetar"
>

<button
    type="submit"
    class="resetar"
>

Resetar Dados

</button>

</form>

</div>


<div class="card">

<h2>Inserir Dados de Teste</h2>

<p>
Executa os INSERTs que estão dentro da seção
[DADOS] do banco.sql.
</p>

<form method="POST" action="index.php">

<input
    type="hidden"
    name="acao"
    value="dados"
>

<button
    type="submit"
    class="dados"
>

Inserir Dados

</button>

</form>

</div>


<?php if (!empty($mensagens)): ?>

<div class="status">

<h2>Status</h2>

<?php

foreach ($mensagens as $msg) {

    echo
    "<div class='msg'>" .
    htmlspecialchars($msg) .
    "</div>";
}

?>

</div>

<?php endif; ?>


</div>

</body>

</html>
