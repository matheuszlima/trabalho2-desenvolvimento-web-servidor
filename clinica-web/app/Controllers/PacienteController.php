<?php

use Carbon\Carbon;

class PacienteController extends ControllerProtegido
{
    private $repositorio;

    public function __construct()
    {
        parent::__construct();
        $this->repositorio = new PacienteRepositorio();
    }

    public function listar()
    {
        $this->view('pacientes/lista', ['pacientes' => $this->repositorio->listar()]);
    }

    public function novo()
    {
        $this->view('pacientes/form', ['paciente' => new Paciente(), 'erros' => []]);
    }

    public function editar()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $paciente = $this->repositorio->buscar($id);

        if ($paciente === null) {
            $this->definirMensagem('erro', 'Paciente não encontrado.');
            $this->redirecionar('/pacientes');
        }

        $this->view('pacientes/form', ['paciente' => $paciente, 'erros' => []]);
    }

    public function salvar()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/pacientes');
        }

        $paciente = new Paciente();
        $paciente->id = (int) ($_POST['id'] ?? 0);
        $paciente->nome = trim($_POST['nome'] ?? '');
        $paciente->cpf = $this->somenteDigitos($_POST['cpf'] ?? '');
        $paciente->telefone = trim($_POST['telefone'] ?? '');
        $paciente->email = trim($_POST['email'] ?? '');
        $paciente->data_nascimento = trim($_POST['data_nascimento'] ?? '');
        $paciente->endereco = trim($_POST['endereco'] ?? '');

        $erros = $this->validar($paciente);

        if (empty($erros)) {
            // Dados normalizados antes de gravar (o CPF e salvo somente com numeros)
            if ($paciente->data_nascimento === '') {
                $paciente->data_nascimento = null;
            }

            try {
                $this->repositorio->salvar($paciente);
                $this->definirMensagem('sucesso', 'Paciente salvo com sucesso!');
                $this->redirecionar('/pacientes');
            } catch (PDOException $e) {
                // 23000 = violacao de chave unica (CPF duplicado em cadastros simultaneos)
                $erros[] = ($e->getCode() === '23000')
                    ? 'Já existe um paciente cadastrado com este CPF.'
                    : 'Erro ao salvar o paciente. Tente novamente.';
            }
        }

        $this->view('pacientes/form', ['paciente' => $paciente, 'erros' => $erros]);
    }

    public function excluir()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/pacientes');
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->repositorio->excluir($id);
            $this->definirMensagem('sucesso', 'Paciente excluído com sucesso!');
        } catch (PDOException $e) {
            $this->definirMensagem('erro', 'Não foi possível excluir: o paciente possui consultas registradas.');
        }

        $this->redirecionar('/pacientes');
    }

    // Validacoes feitas no servidor (nao no HTML)
    private function validar($paciente)
    {
        $erros = [];

        if ($paciente->nome === '') {
            $erros[] = 'O nome é obrigatório.';
        } elseif (strlen($paciente->nome) > 100) {
            $erros[] = 'O nome deve ter no máximo 100 caracteres.';
        }

        if (!$this->cpfValido($paciente->cpf)) {
            $erros[] = 'CPF inválido.';
        } elseif ($this->repositorio->cpfExiste($paciente->cpf, $paciente->id)) {
            $erros[] = 'Já existe um paciente cadastrado com este CPF.';
        }

        if ($paciente->email !== '' && !$this->emailValido($paciente->email)) {
            $erros[] = 'E-mail inválido.';
        }

        if (strlen($paciente->telefone) > 20) {
            $erros[] = 'O telefone deve ter no máximo 20 caracteres.';
        }

        if ($paciente->data_nascimento !== '') {
            if (!$this->dataValida($paciente->data_nascimento, 'Y-m-d')) {
                $erros[] = 'Data de nascimento inválida.';
            } elseif ($paciente->data_nascimento > Carbon::now()->format('Y-m-d')) {
                $erros[] = 'A data de nascimento não pode ser futura.';
            }
        }

        if (strlen($paciente->endereco) > 200) {
            $erros[] = 'O endereço deve ter no máximo 200 caracteres.';
        }

        return $erros;
    }
}
