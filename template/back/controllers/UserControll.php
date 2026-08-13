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