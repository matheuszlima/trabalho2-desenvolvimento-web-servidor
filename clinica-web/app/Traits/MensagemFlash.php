<?php

// Trait (Aula 08): guarda uma mensagem de feedback na sessao para exibir na proxima pagina
trait MensagemFlash
{
    protected function definirMensagem($tipo, $texto)
    {
        $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
    }

    protected function obterMensagem()
    {
        $mensagem = $_SESSION['mensagem'] ?? null;
        unset($_SESSION['mensagem']);
        return $mensagem;
    }
}
