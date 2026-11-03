<?php
// Ponto de entrada unico do sistema (front controller). O .htaccess envia todas as URLs para ca.
session_start();
define('RAIZ', __DIR__);

if (!file_exists(RAIZ . '/vendor/autoload.php')) {
    http_response_code(500);
    echo '<h3>Dependências não instaladas</h3>';
    echo '<p>Execute <code>composer install</code> na pasta do projeto e recarregue a página.</p>';
    exit;
}

// Autoload do Composer: carrega as classes do projeto e os pacotes (Carbon)
require RAIZ . '/vendor/autoload.php';

function paginaDeErro($mensagem)
{
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8"><title>Erro</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>';
    echo '<body><div class="container mt-5"><div class="alert alert-danger">';
    echo '<h4>Ocorreu um erro no sistema</h4>';
    echo '<p class="mb-0">' . htmlspecialchars($mensagem) . '</p>';
    echo '</div></div></body></html>';
}

try {
    $rotas = require RAIZ . '/config/rotas.php';
    $roteador = new Roteador($rotas);
    $roteador->executar();
} catch (ErroAmigavel $e) {
    paginaDeErro($e->getMessage());
} catch (Throwable $e) {
    error_log($e->getMessage());
    paginaDeErro('Erro inesperado. Tente novamente mais tarde.');
}
