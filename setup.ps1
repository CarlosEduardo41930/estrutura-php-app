param([string]$ProjectName = "meu-projeto")

$TargetDir = Join-Path (Get-Location) $ProjectName
$RepoUrl = "https://github.com/CarlosEduardo41930/estrutura-php-app.git"
$TempDir = Join-Path $env:TEMP ([Guid]::NewGuid().ToString())

Write-Host "🚀 Baixando a estrutura completa..." -ForegroundColor Cyan

# Clona temporariamente
git clone --depth 1 $RepoUrl $TempDir | Out-Null

# Copia a pasta template inteira para o projeto de destino
New-Item -ItemType Directory -Force -Path $TargetDir | Out-Null
Copy-Item -Path "$TempDir\template\*" -Destination $TargetDir -Recurse -Force

# Remove temporário
Remove-Item -Path $TempDir -Recurse -Force

Write-Host "✅ Projeto '$ProjectName' criado com sucesso com TODOS os arquivos!" -ForegroundColor Green