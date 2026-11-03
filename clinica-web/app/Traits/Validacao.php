<?php

use Carbon\Carbon;

// Trait (Aula 08): validacoes reaproveitadas por todos os Controllers (feitas no servidor, em PHP)
trait Validacao
{
    // Remove tudo que nao for digito (ex.: 123.456.789-09 vira 12345678909)
    protected function somenteDigitos($texto)
    {
        return preg_replace('/\D/', '', (string) $texto);
    }

    // Valida o CPF pelo algoritmo dos digitos verificadores (recebe somente digitos) - vindo do Trabalho 1
    protected function cpfValido($cpf)
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }
            if ($cpf[$t] != ((10 * $soma) % 11) % 10) {
                return false;
            }
        }
        return true;
    }

    protected function emailValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Confere se o texto e uma data/hora real no formato informado (usa o pacote Carbon)
    protected function dataValida($texto, $formato)
    {
        try {
            return Carbon::parse($texto)->format($formato) === $texto;
        } catch (Exception $e) {
            return false;
        }
    }
}
