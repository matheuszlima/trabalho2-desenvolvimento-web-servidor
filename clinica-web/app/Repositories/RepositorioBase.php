<?php

// Classe abstrata (Aula 07): conexao e operacoes comuns a todos os repositorios
abstract class RepositorioBase
{
    protected $bd;
    protected $tabela;

    public function __construct()
    {
        $this->bd = BD::conectar();
    }

    public function contar()
    {
        $query = $this->bd->prepare("SELECT COUNT(*) AS total FROM " . $this->tabela);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_OBJ);
        return (int) $resultado[0]->total;
    }

    public function excluir($id)
    {
        $query = $this->bd->prepare("DELETE FROM " . $this->tabela . " WHERE id = :id");
        $query->bindParam(':id', $id);
        return $query->execute();
    }
}
