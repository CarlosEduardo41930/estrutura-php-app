param([string]$ProjectName = "meu-projeto-php")

$TargetDir = Join-Path (Get-Location) $ProjectName

Write-Host "🚀 Criando estrutura PHP em: $TargetDir" -ForegroundColor Cyan

# Pastas
$Folders = @(
    "back\banco", "back\config", "back\controllers", "back\helpers",
    "back\middleware", "back\models", "back\security", "back\service",
    "database", "front\public", "front\src\components", "front\src\pictures",
    "front\src\routes", "front\src\scripts", "front\src\styles", "front\src\views"
)

foreach ($Folder in $Folders) {
    New-Item -ItemType Directory -Force -Path "$TargetDir\$Folder" | Out-Null
}

# Criar .htaccess da raiz
@"
RewriteEngine On

RewriteRule ^database/index\.php$ - [L]
RewriteRule ^database/(.*)$ database/index.php [L,QSA]

RewriteRule ^$ front/public/index.php [L]
RewriteRule ^(.+)$ front/public/index.php [L,QSA]
"@ | Set-Content "$TargetDir\.htaccess"

# Criar .htaccess do Backend
@"
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
"@ | Set-Content "$TargetDir\back\.htaccess"

# Criar config.php
@"
<?php

`$host = "localhost";
`$db   = "nome _do_banco";
`$user = "nome_do_usuario";
`$pass = "senha_do_banco";
`$charset = "utf8mb4";

`$conexao = "mysql:host=`$host;dbname=`$db;charset=`$charset";

try {
    `$pdo = new PDO(`$conexao, `$user, `$pass);
    `$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    `$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    `$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException `$e) {
    die("Erro na conexão: " . `$e->getMessage());
}
?>
"@ | Set-Content "$TargetDir\back\config\config.php"

# Criar arquivos adicionais
New-Item -ItemType File -Force -Path "$TargetDir\back\banco\banco.sql" | Out-Null
New-Item -ItemType File -Force -Path "$TargetDir\front\src\scripts\scripts.js" | Out-Null
New-Item -ItemType File -Force -Path "$TargetDir\front\src\styles\styles.css" | Out-Null

Write-Host "✅ Projeto '$ProjectName' criado com sucesso!" -ForegroundColor Green