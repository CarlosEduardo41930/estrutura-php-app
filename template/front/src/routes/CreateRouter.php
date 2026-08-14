<?php

/*
 * ==========================================================
 * DETECTA A PASTA RAIZ DO PROJETO AUTOMATICAMENTE
 * Funciona em qualquer pasta local ou servidor de hospedagem
 * ==========================================================
 */

$docRoot  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$arquivo  = str_replace('\\', '/', __DIR__);

// Sobe 3 níveis (src/routes -> src -> front -> RAIZ DO PROJETO)
$rootDir  = str_replace('\\', '/', dirname(dirname(dirname($arquivo))));

// Calcula o prefixo da URL dinamicamente
$BASE = str_replace($docRoot, '', $rootDir);

if ($BASE === '/' || $BASE === '\\') {
    $BASE = '';
}

class CreateRouter
{
    private array $routes = [];

    public function get(string $path, callable $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch(): void
    {
        global $BASE;

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o prefixo da pasta base da URL se ele existir
        if (!empty($BASE) && str_starts_with($uri, $BASE)) {
            $uri = substr($uri, strlen($BASE));
        }

        // Garante que a barra inicial exista
        if ($uri === '' || $uri === false) {
            $uri = '/';
        }

        $callback = $this->routes[$method][$uri] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo 'Página não encontrada';
            return;
        }

        call_user_func($callback);
    }
}

?>