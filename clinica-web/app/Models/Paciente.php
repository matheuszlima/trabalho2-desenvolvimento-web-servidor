<?php

use Carbon\Carbon;

// Entidade mapeada da tabela pacientes (PDO::FETCH_CLASS - Aula 06)
class Paciente
{
    public $id;
    public $nome;
    public $cpf;
    public $telefone;
    public $email;
    public $data_nascimento;
    public $endereco;

    // Usa o pacote Carbon (Composer) para formatar a data
    public function dataNascimentoFormatada()
    {
        if (empty($this->data_nascimento)) {
            return '';
        }
        return Carbon::parse($this->data_nascimento)->format('d/m/Y');
    }

    public function cpfFormatado()
    {
        $cpf = (string) $this->cpf;
        if (strlen($cpf) !== 11) {
            return $cpf;
        }
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
}
