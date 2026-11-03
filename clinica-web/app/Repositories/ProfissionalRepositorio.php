<?php

class ProfissionalRepositorio extends RepositorioBase implements Crud
{
    protected $tabela = 'profissionais';

    public function listar()
    {
        $query = $this->bd->prepare("SELECT * FROM profissionais ORDER BY nome");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Profissional::class);
    }

    public function buscar($id)
    {
        $query = $this->bd->prepare("SELECT * FROM profissionais WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_CLASS, Profissional::class);
        return $resultado[0] ?? null;
    }

    public function salvar($profissional)
    {
        if (empty($profissional->id)) {
            $query = $this->bd->prepare(
                "INSERT INTO profissionais (nome, especialidade, registro, telefone)
                 VALUES (:nome, :especialidade, :registro, :telefone)"
            );
        } else {
            $query = $this->bd->prepare(
                "UPDATE profissionais SET nome = :nome, especialidade = :especialidade,
                 registro = :registro, telefone = :telefone WHERE id = :id"
            );
            $query->bindParam(':id', $profissional->id);
        }

        $query->bindParam(':nome', $profissional->nome);
        $query->bindParam(':especialidade', $profissional->especialidade);
        $query->bindParam(':registro', $profissional->registro);
        $query->bindParam(':telefone', $profissional->telefone);

        return $query->execute();
    }
}
