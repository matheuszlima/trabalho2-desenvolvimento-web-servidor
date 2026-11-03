<?php

class UsuarioRepositorio extends RepositorioBase
{
    protected $tabela = 'usuarios';

    public function buscarPorEmail($email)
    {
        $query = $this->bd->prepare("SELECT * FROM usuarios WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_CLASS, Usuario::class);
        return $resultado[0] ?? null;
    }
}
