<?php

/*
 * ==========================================================
 * DETECTA O CAMINHO DO PROJETO AUTOMATICAMENTE
 * Não precisa mudar nada entre local e hospedagem
 * ==========================================================
 */

$docRoot  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$arquivo  = str_replace('\\', '/', __DIR__);
$frontDir = dirname(dirname($arquivo));

$BASE = str_replace($docRoot, '', $frontDir);

if ($BASE === '' || $BASE === '/') {
    $BASE = '';
}

/*
 * Corrige o CreateRouter para funcionar
 * tanto na raiz quanto em subpasta
 */

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

        if (!empty($BASE) && str_starts_with($uri, $BASE)) {
            $uri = substr($uri, strlen($BASE));
        }

        if ($uri === '') {
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