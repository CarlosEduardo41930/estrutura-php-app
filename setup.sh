#!/usr/bin/env bash
set -e

PROJECT_NAME="${1:-meu-projeto}"
TARGET_DIR="$(pwd)/$PROJECT_NAME"
REPO_URL="https://github.com/CarlosEduardo41930/estrutura-php-app.git"
TEMP_DIR="$(mktemp -d)"

echo "🚀 Baixando a estrutura completa..."

# Clona temporariamente o repositório
git clone --depth 1 "$REPO_URL" "$TEMP_DIR" > /dev/null 2>&1

# Move a pasta template para a pasta do projeto final
mkdir -p "$TARGET_DIR"
cp -r "$TEMP_DIR/template/." "$TARGET_DIR/"

# Limpa os arquivos temporários
rm -rf "$TEMP_DIR"

echo "✅ Projeto '$PROJECT_NAME' criado com sucesso com TODOS os arquivos!"