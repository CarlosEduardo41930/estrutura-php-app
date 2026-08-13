#!/usr/bin/env node

import fs from 'fs-extra';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const projectName = process.argv[2] || 'meu-projeto';
const targetDir = path.join(process.cwd(), projectName);
const templateDir = path.join(__dirname, 'template');

async function setup() {
  console.log(`🚀 Criando projeto '${projectName}' em: ${targetDir}`);

  // Copia toda a estrutura e arquivos da pasta template/
  await fs.copy(templateDir, targetDir);

  console.log(`\n✅ Projeto '${projectName}' criado com sucesso com TODOS os arquivos!`);
}

setup().catch((err) => console.error('❌ Erro:', err));