#!/usr/bin/env node

import fs from 'fs-extra';
import path from 'path';
import { execa } from 'execa';
import chalk from 'chalk';

const projectName = process.argv[2] || 'meu-projeto-php';
const rootDir = path.join(process.cwd(), projectName);

async function setup() {
  console.log(chalk.blue(`\n🚀 Criando o projeto em: ${rootDir}\n`));

  // 1. Criar estrutura de pastas
  const folders = [
    'back/banco', 'back/config', 'back/controllers', 'back/helpers',
    'back/middleware', 'back/models', 'back/security', 'back/service',
    'database', 'front/public', 'front/src/components', 'front/src/pictures',
    'front/src/routes', 'front/src/scripts', 'front/src/styles', 'front/src/views'
  ];

  for (const folder of folders) {
    await fs.ensureDir(path.join(rootDir, folder));
  }

  // 2. Criar arquivos da Raiz
  await fs.writeFile(path.join(rootDir, '.htaccess'), `RewriteEngine On\n\nRewriteRule ^database/index\\.php$ - [L]\nRewriteRule ^database/(.*)$ database/index.php [L,QSA]\n\nRewriteRule ^$ front/public/index.php [L]\nRewriteRule ^(.+)$ front/public/index.php [L,QSA]\n`);

  // 3. Criar arquivos do Backend
  await fs.writeFile(path.join(rootDir, 'back/.htaccess'), `<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n`);
  await fs.writeFile(path.join(rootDir, 'back/banco/banco.sql'), `-- [BANCO]\nCREATE DATABASE IF NOT EXISTS \`chamada\` DEFAULT CHARACTER SET utf8mb4;\n\n-- [TABELAS]\n\n-- [DADOS]\n`);
  
  await fs.writeFile(path.join(rootDir, 'back/config/config.php'), `<?php\n\n$host = "localhost";\n$db   = "nome _do_banco";\n$user = "nome_do_usuario";\n$pass = "senha_do_banco";\n$charset = "utf8mb4";\n\n$conexao = "mysql:host=$host;dbname=$db;charset=$charset";\n\ntry {\n    $pdo = new PDO($conexao, $user, $pass);\n    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);\n} catch (PDOException $e) {\n    die("Erro na conexão: " . $e->getMessage());\n}\n\n?>\n`);

  await fs.writeFile(path.join(rootDir, 'back/controllers/UserControll.php'), `<?php\n\nif (session_status() === PHP_SESSION_NONE) {\n    session_start();\n    $_SESSION['erro'] = [];\n}\n\nrequire __DIR__ . "/../models/UserModel.php";\nrequire __DIR__ . '/../config/config.php';\nrequire __DIR__ . '/../security/UseSecurity.php';\nrequire __DIR__ . '/../middleware/UseMiddleware.php';\nrequire __DIR__ . '/../helpers/UseHelpers.php';\n`);

  await fs.writeFile(path.join(rootDir, 'back/helpers/UseHelpers.php'), `<?php\n\nfunction traduz_data_para_exibir($data)\n{\n    if ($data == "" or $data == "0000-00-00") {\n        return "";\n    }\n    $dados = explode("-", $data);\n    $data_exibir = "{$dados[2]}/{$dados[1]}/{$dados[0]}";\n    return $data_exibir;\n}\n\n?>\n`);

  await fs.writeFile(path.join(rootDir, 'back/middleware/UseMiddleware.php'), `<?php\n\nsession_start();\n\nif (isset($_POST['nivel']) && $_POST['nivel'] == 'nivel1') {\n    $_SESSION['nivel'] = $_POST['nivel'];\n    header('Location: ../views/pagina_nivel1.php');\n    exit();\n} elseif (isset($_POST['nivel']) && $_POST['nivel'] == 'nivel2') {\n    $_SESSION['nivel'] = $_POST['nivel'];\n    header('Location: ../views/pagina_nivel2.php');\n    exit();\n}\n?>\n`);

  await fs.writeFile(path.join(rootDir, 'back/models/UserModel.php'), `<?php\n// exemplo de função de banco de dados\n?>\n`);

  await fs.writeFile(path.join(rootDir, 'back/security/UseSecurity.php'), `<?php\n\nfunction sanitizar(string $dado, string $tipo = 'texto'): string\n{\n    $dado = trim($dado);\n    $dado = preg_replace('/[\\x00-\\x1F\\x7F]/u', '', $dado);\n    $dado = strip_tags($dado);\n    switch ($tipo) {\n        case 'nome':\n            $dado = preg_replace('/[^a-zA-ZÀ-ÿ\\s\\-]/u', '', $dado);\n            $dado = preg_replace('/\\s+/', ' ', $dado);\n            break;\n        case 'email':\n            $dado = filter_var($dado, FILTER_SANITIZE_EMAIL);\n            break;\n        case 'inteiro':\n            $dado = preg_replace('/[^0-9\\-]/', '', $dado);\n            break;\n    }\n    if ($tipo === 'nome') {\n        $tamanho = mb_strlen($dado, 'UTF-8');\n        if ($tamanho < 2 || $tamanho > 50) {\n            $_SESSION['erro'][] = "Nome: Nome deve ter entre 2 e 50 caracteres.";\n        }\n    }\n    return $dado;\n}\n\nfunction validarSenha(string $senha): void\n{\n    if (strlen($senha) < 6) {\n        $_SESSION['erro'][] = "Senha: Mínimo 6 caracteres";\n    }\n    if (!preg_match('/[A-Z]/', $senha)) {\n        $_SESSION['erro'][] = "Senha: Pelo menos uma letra maiúscula";\n    }\n    if (!preg_match('/[0-9]/', $senha)) {\n        $_SESSION['erro'][] = "Senha: Pelo menos um número";\n    }\n}\n\nfunction confirmarSenha(string $senha, string $confirmar_senha)\n{\n    if ($senha !== $confirmar_senha) {\n        $_SESSION['erro'][] = 'As senhas não coincidem.';\n    }\n}\n\nfunction validarCPF($cpf)\n{\n    try {\n        $cpf = preg_replace('/[^0-9]/', '', $cpf);\n        if (strlen($cpf) != 11) {\n            $_SESSION['erro'][] = "CPF deve conter 11 dígitos";\n        }\n        if (preg_match('/(\\d)\\1{10}/', $cpf)) {\n            $_SESSION['erro'][] = "CPF inválido";\n        }\n        $soma = 0;\n        for ($i = 0; $i < 9; $i++) {\n            $soma += $cpf[$i] * (10 - $i);\n        }\n        $dig1 = ($soma * 10) % 11;\n        if ($dig1 == 10) $dig1 = 0;\n\n        $soma = 0;\n        for ($i = 0; $i < 10; $i++) {\n            $soma += $cpf[$i] * (11 - $i);\n        }\n        $dig2 = ($soma * 10) % 11;\n        if ($dig2 == 10) $dig2 = 0;\n\n        if ($cpf[9] == $dig1 && $cpf[10] == $dig2) {\n            return true;\n        } else {\n            $_SESSION['erro'][] = "CPF inválido";\n        }\n    } catch (Exception $e) {\n        $_SESSION['erro'][] = "Erro ao cadastrar: " . $e->getMessage();\n    }\n}\n`);

  await fs.writeFile(path.join(rootDir, 'back/service/UseService.php'), `<?php\n// Aqui você pode colocar serviços de fora do seu projeto, como APIs externas, serviços de pagamento, etc.\n?>\n`);

  // 4. Criar arquivos da Database
  await fs.writeFile(path.join(rootDir, 'database/config_banco_uso.php'), `<?php\n\n$host = "localhost";\n$user = "root";\n$password = "";\n$banco = "chamada";\n`);

  // (Database index.php resumido/estruturado)
  await fs.writeFile(path.join(rootDir, 'database/index.php'), `<?php\nrequire_once "config_banco_uso.php";\n$mensagens = [];\n$arquivoSQL = __DIR__ . "/../back/banco/banco.sql";\n// Código do Painel do Banco inserido aqui...\n?>\n`);

  // 5. Criar arquivos do Frontend
  await fs.writeFile(path.join(rootDir, 'front/public/.htaccess'), `RewriteEngine On\n\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^ index.php [L,QSA]\n`);
  
  await fs.writeFile(path.join(rootDir, 'front/public/index.php'), `<?php\n\nrequire_once '../src/routes/createRouter.php';\n\n$router = new CreateRouter();\n\n$router->get('/', function () {\n    require '../src/views/home.php';\n});\n\n$router->get('/database/configuracao', function () {\n    require '../../database/index.php';\n});\n\n$router->dispatch();\n`);

  await fs.writeFile(path.join(rootDir, 'front/src/components/useComponents.php'), `<?php\n\nfunction mostrarTabela()\n{\n    $usuarios = [\n        ['id' => 1, 'nome' => 'João', 'email' => 'joao@email.com'],\n        ['id' => 2, 'nome' => 'Maria', 'email' => 'maria@email.com'],\n        ['id' => 3, 'nome' => 'Carlos', 'email' => 'carlos@email.com']\n    ];\n\n    echo '<table>';\n    echo '<thead><tr><th>ID</th><th>Nome</th><th>E-mail</th></tr></thead>';\n    echo '<tbody>';\n    foreach ($usuarios as $usuario) {\n        echo '<tr>';\n        echo '<td>' . $usuario['id'] . '</td>';\n        echo '<td>' . $usuario['nome'] . '</td>';\n        echo '<td>' . $usuario['email'] . '</td>';\n        echo '</tr>';\n    }\n    echo '</tbody></table>';\n}\n`);

  await fs.writeFile(path.join(rootDir, 'front/src/routes/CreateRouter.php'), `<?php\n\nclass CreateRouter\n{\n    private array $routes = [];\n\n    public function get(string $path, callable $callback): void\n    {\n        $this->routes['GET'][$path] = $callback;\n    }\n\n    public function post(string $path, callable $callback): void\n    {\n        $this->routes['POST'][$path] = $callback;\n    }\n\n    public function dispatch(): void\n    {\n        $method = $_SERVER['REQUEST_METHOD'];\n        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);\n        $basePath = '/treinar/chamada';\n\n        if (str_starts_with($uri, $basePath)) {\n            $uri = substr($uri, strlen($basePath));\n        }\n\n        if ($uri === '') $uri = '/';\n\n        $callback = $this->routes[$method][$uri] ?? null;\n\n        if (!$callback) {\n            http_response_code(404);\n            echo 'Página não encontrada';\n            return;\n        }\n\n        call_user_func($callback);\n    }\n}\n`);

  await fs.writeFile(path.join(rootDir, 'front/src/scripts/scripts.js'), `// Scripts Globais\n`);
  await fs.writeFile(path.join(rootDir, 'front/src/styles/styles.css'), `/* Estilos Globais */\n`);

  await fs.writeFile(path.join(rootDir, 'front/src/views/home.php'), `<?php\nrequire_once '../src/components/useComponents.php';\n?>\n<!DOCTYPE html>\n<html lang="pt-BR">\n<head>\n    <meta charset="UTF-8">\n    <title>Minha Tabela</title>\n    <style>\n        body { font-family: Arial, sans-serif; margin: 40px; }\n        table { width: 100%; border-collapse: collapse; }\n        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }\n        th { background-color: #f2f2f2; }\n    </style>\n</head>\n<body>\n    <h1>Lista de Usuários</h1>\n    <?php mostrarTabela(); ?>\n</body>\n</html>\n`);

  console.log(chalk.green('\n✅ Projeto PHP criado com sucesso!'));
}

setup().catch((err) => console.error(chalk.red('❌ Erro:'), err));