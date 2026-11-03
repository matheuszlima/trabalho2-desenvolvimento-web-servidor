<?php

// Mecanismo de rotas (Aula 08): associa a URL acessada a um Controller e a um metodo
class Roteador
{
    private $rotas;

    public function __construct($rotas)
    {
        $this->rotas = $rotas;
    }

    public function executar()
    {
        // Todo POST precisa trazer o token CSRF valido
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::valido()) {
            $this->exibirErro(403, 'erros/403');
            return;
        }

        $url = $this->caminhoRequisitado();

        if (array_key_exists($url, $this->rotas)) {
            $classe = $this->rotas[$url][0];
            $metodo = $this->rotas[$url][1];

            $controller = new $classe();
            $controller->$metodo();
        } else {
            $this->exibirErro(404, 'erros/404');
        }
    }

    // Recupera a URI que o cliente quer acessar, sem a pasta do projeto e sem a query string
    private function caminhoRequisitado()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $base = Url::base();

        if ($base !== '' && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        $uri = rtrim($uri, '/');

        if ($uri === '') {
            $uri = '/';
        }

        return $uri;
    }

    private function exibirErro($codigo, $view)
    {
        http_response_code($codigo);
        $dados = [];
        $mensagem = null;
        $usuarioLogado = $_SESSION['usuario'] ?? null;
        require RAIZ . '/app/Views/' . $view . '.php';
    }
}
