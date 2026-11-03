<?php

use Carbon\Carbon;

// Entidade mapeada da tabela consultas (PDO::FETCH_CLASS - Aula 06)
class Consulta
{
    public $id;
    public $paciente_id;
    public $profissional_id;
    public $data_hora;
    public $status;
    public $observacoes;

    // Campos extras que vem do JOIN na listagem
    public $paciente_nome;
    public $profissional_nome;

    // Status possiveis: valor no banco => texto exibido
    public static $statusDisponiveis = [
        'agendada'   => 'Agendada',
        'confirmada' => 'Confirmada',
        'realizada'  => 'Realizada',
        'cancelada'  => 'Cancelada',
    ];

    // Classe de cor do Bootstrap para cada status
    public static $coresStatus = [
        'agendada'   => 'primary',
        'confirmada' => 'success',
        'realizada'  => 'secondary',
        'cancelada'  => 'danger',
    ];

    public function dataHoraFormatada()
    {
        if (empty($this->data_hora)) {
            return '';
        }
        return Carbon::parse($this->data_hora)->format('d/m/Y H:i');
    }

    // Formato exigido pelo campo <input type="datetime-local">
    public function dataHoraParaFormulario()
    {
        if (empty($this->data_hora)) {
            return '';
        }
        return Carbon::parse($this->data_hora)->format('Y-m-d\TH:i');
    }

    public function statusFormatado()
    {
        return self::$statusDisponiveis[$this->status] ?? $this->status;
    }

    public function corStatus()
    {
        return self::$coresStatus[$this->status] ?? 'secondary';
    }
}
