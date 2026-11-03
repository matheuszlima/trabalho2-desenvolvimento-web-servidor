<?php

// Classe responsavel pela conexao com o banco via PDO (metodo estatico, como na Aula 07)
class BD
{
    private static $conexao = null;

    public static function conectar()
    {
        if (self::$conexao === null) {
            $config = require RAIZ . '/config/config.php';
            $bd = $config['bd'];

            try {
                self::$conexao = new PDO(
                    'mysql:host=' . $bd['host'] . ';dbname=' . $bd['nome'] . ';charset=utf8mb4',
                    $bd['usuario'],
                    $bd['senha'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                // Detalhes tecnicos vao para o log; o usuario ve uma mensagem simples
                error_log('Erro de conexão: ' . $e->getMessage());
                throw new ErroAmigavel('Não foi possível conectar ao banco de dados. Verifique o arquivo config/config.php.');
            }
        }

        return self::$conexao;
    }
}
