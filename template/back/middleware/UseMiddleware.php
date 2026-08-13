<?php

session_start();

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