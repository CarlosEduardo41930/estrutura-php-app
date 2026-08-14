
<?php


/*
 * ==========================================================
 * VERIFICAR LOGIN
 * Redireciona para login.php se não estiver logado
 * ==========================================================
 */

function verificarLogin()
{
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header('Location: login.php');
        exit();
    }
}

/*
 * ==========================================================
 * VERIFICAR NÍVEL DE ACESSO
 * ==========================================================
 */

function verificarNivel($nivel_necessario)
{
    verificarLogin();

    if (($_SESSION['usuario_nivel'] ?? '') !== $nivel_necessario) {
        $_SESSION['erro'][] = "Acesso não autorizado!";
        header('Location: home.php');
        exit();
    }
}

/*
 * ==========================================================
 * SELECIONAR NÍVEL (exemplo do seu padrão original)
 * ==========================================================
 */

if (isset($_POST['nivel']) && $_POST['nivel'] == 'nivel1') {

    $_SESSION['nivel'] = $_POST['nivel'];

    header('Location: ../views/pagina_nivel1.php');
    exit();

} elseif (isset($_POST['nivel']) && $_POST['nivel'] == 'nivel2') {

    $_SESSION['nivel'] = $_POST['nivel'];

    header('Location: ../views/pagina_nivel2.php');
    exit();
}

?>