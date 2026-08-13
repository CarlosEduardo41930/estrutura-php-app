#!/usr/bin/env bash
set -e

PROJECT_NAME="${1:-meu-projeto-php}"
TARGET_DIR="$(pwd)/$PROJECT_NAME"

echo "🚀 Criando estrutura PHP em: $TARGET_DIR..."

# Pastas
mkdir -p "$TARGET_DIR/back/banco"
mkdir -p "$TARGET_DIR/back/config"
mkdir -p "$TARGET_DIR/back/controllers"
mkdir -p "$TARGET_DIR/back/helpers"
mkdir -p "$TARGET_DIR/back/middleware"
mkdir -p "$TARGET_DIR/back/models"
mkdir -p "$TARGET_DIR/back/security"
mkdir -p "$TARGET_DIR/back/service"
mkdir -p "$TARGET_DIR/database"
mkdir -p "$TARGET_DIR/front/public"
mkdir -p "$TARGET_DIR/front/src/components"
mkdir -p "$TARGET_DIR/front/src/pictures"
mkdir -p "$TARGET_DIR/front/src/routes"
mkdir -p "$TARGET_DIR/front/src/scripts"
mkdir -p "$TARGET_DIR/front/src/styles"
mkdir -p "$TARGET_DIR/front/src/views"

# Arquivo .htaccess na Raiz
cat << 'EOF' > "$TARGET_DIR/.htaccess"
RewriteEngine On

RewriteRule ^database/index\.php$ - [L]
RewriteRule ^database/(.*)$ database/index.php [L,QSA]

RewriteRule ^$ front/public/index.php [L]
RewriteRule ^(.+)$ front/public/index.php [L,QSA]
EOF

# Arquivo .htaccess do Back
cat << 'EOF' > "$TARGET_DIR/back/.htaccess"
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
EOF

# Arquivo config.php
cat << 'EOF' > "$TARGET_DIR/back/config/config.php"
<?php

$host = "localhost";
$db   = "nome _do_banco";
$user = "nome_do_usuario";
$pass = "senha_do_banco";
$charset = "utf8mb4";

$conexao = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($conexao, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

?>
EOF

# Arquivos em branco adicionais
touch "$TARGET_DIR/back/banco/banco.sql"
touch "$TARGET_DIR/front/src/scripts/scripts.js"
touch "$TARGET_DIR/front/src/styles/styles.css"

echo ""
echo "✅ Projeto '$PROJECT_NAME' gerado com sucesso!"