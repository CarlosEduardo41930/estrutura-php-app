param([string]$ProjectName = "meu-projeto")

$TargetDir = Join-Path (Get-Location) $ProjectName
$ZipUrl = "https://github.com/CarlosEduardo41930/estrutura-php-app/archive/refs/heads/main.zip"
$TempZip = Join-Path $env:TEMP "repo_temp.zip"
$TempExtract = Join-Path $env:TEMP ([Guid]::NewGuid().ToString())

Write-Host "🚀 Baixando a estrutura completa via ZIP..." -ForegroundColor Cyan

try {
    # 1. Baixar o ZIP direto do GitHub sem precisar do Git
    Invoke-WebRequest -Uri $ZipUrl -OutFile $TempZip -UseBasicParsing

    # 2. Descompactar o ZIP no diretório temporário
    Expand-Archive -Path $TempZip -DestinationPath $TempExtract -Force

    # 3. Caminho de onde a pasta 'template' foi extraída
    $TemplatePath = Join-Path $TempExtract "estrutura-php-app-main\template"

    if (Test-Path $TemplatePath) {
        # 4. Criar pasta do projeto e copiar os arquivos
        New-Item -ItemType Directory -Force -Path $TargetDir | Out-Null
        Copy-Item -Path "$TemplatePath\*" -Destination $TargetDir -Recurse -Force
        Write-Host "✅ Projeto '$ProjectName' criado com sucesso com TODOS os arquivos!" -ForegroundColor Green
    } else {
        Write-Host "❌ Erro: Pasta 'template' não foi encontrada no repositório." -ForegroundColor Red
    }
}
catch {
    Write-Host "❌ Erro ao baixar ou extrair os arquivos: $_" -ForegroundColor Red
}
finally {
    # 5. Limpar arquivos temporários
    if (Test-Path $TempZip) { Remove-Item -Path $TempZip -Force }
    if (Test-Path $TempExtract) { Remove-Item -Path $TempExtract -Recurse -Force }
}