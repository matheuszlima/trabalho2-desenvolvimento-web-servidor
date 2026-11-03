<?php

// Trait (Aula 08): verifica a sessao. Se o usuario nao estiver logado, redireciona para o login
trait ExigeLogin
{
    protected function exigirLogin()
    {
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== "true") {
            header('Location: ' . Url::para('/login'));
            exit;
        }
    }
}
