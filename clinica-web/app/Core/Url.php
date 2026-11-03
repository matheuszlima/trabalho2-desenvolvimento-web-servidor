<?php

// Monta as URLs do sistema considerando a pasta onde o projeto esta instalado
class Url
{
    public static function base()
    {
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        return rtrim($base, '/');
    }

    public static function para($caminho)
    {
        return self::base() . $caminho;
    }
}
