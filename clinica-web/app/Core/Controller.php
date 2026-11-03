<?php

// Classe abstrata: modelo para todos os Controllers (Aula 07 - classe abstrata e traits da Aula 08)
abstract class Controller
{
    use MensagemFlash, Validacao;

    // Carrega uma View passando os dados por meio da variavel $dados
    protected function view($arquivo, $dados = [])
    {
        $mensagem = $this->obterMensagem();
        $usuarioLogado = $_SESSION['usuario'] ?? null;
        require RAIZ . '/app/Views/' . $arquivo . '.php';
    }

    protected function redirecionar($caminho)
    {
        header('Location: ' . Url::para($caminho));
        exit;
    }

    protected function requisicaoPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
