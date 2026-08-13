param([string]$ProjectName = "meu-projeto")

$TargetDir = Join-Path (Get-Location) $ProjectName
$ZipUrl = "https://github.com/CarlosEduardo41930/estrutura-php-app/archive/refs/heads/main.zip"
$TempZip = Join-Path $env:TEMP "repo_temp.zip"
$TempExtract = Join-Path $env:TEMP ([Guid]::NewGuid().ToString())

Write-Host "🚀 Baixando a estrutura completa..." -ForegroundColor Cyan

try {
    # 1. Baixa o arquivo ZIP do GitHub (Nativo do Windows)
    Invoke-WebRequest -Uri $ZipUrl -OutFile $TempZip -UseBasicParsing

    # 2. Descompacta no diretório temporário
    Expand-Archive -Path $TempZip -DestinationPath $TempExtract -Force

    # 3. Localiza a pasta template dentro do ZIP descompactado
    $TemplatePath = Join-Path $TempExtract "estrutura-php-app-main\template"

    if (Test-Path $TemplatePath) {
        # 4. Criar a pasta final e copiar todos os arquivos
        New-Item -ItemType Directory -Force -Path $TargetDir | Out-Null
        Copy-Item -Path "$TemplatePath\*" -Destination $TargetDir -Recurse -Force
        Write-Host "`n✅ Projeto '$ProjectName' criado com sucesso com TODOS os arquivos!" -ForegroundColor Green
    } else {
        Write-Host "`n❌ Erro: A pasta 'template' não foi encontrada dentro do repositório." -ForegroundColor Red
    }
}
catch {
    Write-Host "`n❌ Erro ao baixar ou descompactar os arquivos: $_" -ForegroundColor Red
}
finally {
    # 5. Limpa os arquivos temporários
    if (Test-Path $TempZip) { Remove-Item -Path $TempZip -Force }
    if (Test-Path $TempExtract) { Remove-Item -Path $TempExtract -Recurse -Force }
}