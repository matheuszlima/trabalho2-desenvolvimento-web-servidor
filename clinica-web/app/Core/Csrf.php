<?php

// Protecao contra CSRF (mantida do Trabalho 1): um token por sessao, enviado em todo formulario POST
class Csrf
{
    public static function token()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    // Campo escondido para colocar dentro dos formularios
    public static function campo()
    {
        return '<input type="hidden" name="csrf" value="' . htmlspecialchars(self::token()) . '">';
    }

    public static function valido()
    {
        $enviado = $_POST['csrf'] ?? '';
        return is_string($enviado) && hash_equals(self::token(), $enviado);
    }
}
