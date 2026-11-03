<?php

class PacienteRepositorio extends RepositorioBase implements Crud
{
    protected $tabela = 'pacientes';

    public function listar()
    {
        $query = $this->bd->prepare("SELECT * FROM pacientes ORDER BY nome");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Paciente::class);
    }

    public function buscar($id)
    {
        $query = $this->bd->prepare("SELECT * FROM pacientes WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_CLASS, Paciente::class);
        return $resultado[0] ?? null;
    }

    // Verifica se ja existe outro paciente com o mesmo CPF
    public function cpfExiste($cpf, $ignorarId = 0)
    {
        $query = $this->bd->prepare("SELECT id FROM pacientes WHERE cpf = :cpf AND id <> :id");
        $query->bindParam(':cpf', $cpf);
        $query->bindParam(':id', $ignorarId);
        $query->execute();
        return count($query->fetchAll(PDO::FETCH_OBJ)) > 0;
    }

    public function salvar($paciente)
    {
        if (empty($paciente->id)) {
            $query = $this->bd->prepare(
                "INSERT INTO pacientes (nome, cpf, telefone, email, data_nascimento, endereco)
                 VALUES (:nome, :cpf, :telefone, :email, :data_nascimento, :endereco)"
            );
        } else {
            $query = $this->bd->prepare(
                "UPDATE pacientes SET nome = :nome, cpf = :cpf, telefone = :telefone, email = :email,
                 data_nascimento = :data_nascimento, endereco = :endereco WHERE id = :id"
            );
            $query->bindParam(':id', $paciente->id);
        }

        $query->bindParam(':nome', $paciente->nome);
        $query->bindParam(':cpf', $paciente->cpf);
        $query->bindParam(':telefone', $paciente->telefone);
        $query->bindParam(':email', $paciente->email);
        $query->bindParam(':data_nascimento', $paciente->data_nascimento);
        $query->bindParam(':endereco', $paciente->endereco);

        return $query->execute();
    }
}
