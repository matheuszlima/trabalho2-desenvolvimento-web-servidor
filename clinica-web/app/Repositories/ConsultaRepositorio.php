<?php

class ConsultaRepositorio extends RepositorioBase implements Crud
{
    protected $tabela = 'consultas';

    // Consulta com JOIN para trazer o nome do paciente e do profissional
    private $sqlBase = "SELECT c.*, p.nome AS paciente_nome, pr.nome AS profissional_nome
                        FROM consultas c
                        JOIN pacientes p ON p.id = c.paciente_id
                        JOIN profissionais pr ON pr.id = c.profissional_id";

    // Lista todas as consultas, ou somente as de um status (filtro opcional)
    public function listar($status = '')
    {
        $sql = $this->sqlBase;
        if ($status !== '') {
            $sql .= " WHERE c.status = :status";
        }
        $sql .= " ORDER BY c.data_hora DESC";

        $query = $this->bd->prepare($sql);
        if ($status !== '') {
            $query->bindParam(':status', $status);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Consulta::class);
    }

    // Proximas consultas ainda nao realizadas (usado na pagina inicial)
    public function proximas()
    {
        $query = $this->bd->prepare(
            $this->sqlBase . " WHERE c.data_hora >= NOW() AND c.status IN ('agendada', 'confirmada')
                               ORDER BY c.data_hora ASC LIMIT 5"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Consulta::class);
    }

    public function buscar($id)
    {
        $query = $this->bd->prepare("SELECT * FROM consultas WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_CLASS, Consulta::class);
        return $resultado[0] ?? null;
    }

    // Verifica se o profissional ja tem outra consulta (nao cancelada) no mesmo horario
    public function existeConflito($profissionalId, $dataHora, $ignorarId = 0)
    {
        $query = $this->bd->prepare(
            "SELECT id FROM consultas
             WHERE profissional_id = :profissional_id AND data_hora = :data_hora
             AND status <> 'cancelada' AND id <> :id"
        );
        $query->bindParam(':profissional_id', $profissionalId);
        $query->bindParam(':data_hora', $dataHora);
        $query->bindParam(':id', $ignorarId);
        $query->execute();
        return count($query->fetchAll(PDO::FETCH_OBJ)) > 0;
    }

    public function salvar($consulta)
    {
        if (empty($consulta->id)) {
            $query = $this->bd->prepare(
                "INSERT INTO consultas (paciente_id, profissional_id, data_hora, status, observacoes)
                 VALUES (:paciente_id, :profissional_id, :data_hora, :status, :observacoes)"
            );
        } else {
            $query = $this->bd->prepare(
                "UPDATE consultas SET paciente_id = :paciente_id, profissional_id = :profissional_id,
                 data_hora = :data_hora, status = :status, observacoes = :observacoes WHERE id = :id"
            );
            $query->bindParam(':id', $consulta->id);
        }

        $query->bindParam(':paciente_id', $consulta->paciente_id);
        $query->bindParam(':profissional_id', $consulta->profissional_id);
        $query->bindParam(':data_hora', $consulta->data_hora);
        $query->bindParam(':status', $consulta->status);
        $query->bindParam(':observacoes', $consulta->observacoes);

        return $query->execute();
    }
}
