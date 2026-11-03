<?php

class ProfissionalController extends ControllerProtegido
{
    private $repositorio;

    public function __construct()
    {
        parent::__construct();
        $this->repositorio = new ProfissionalRepositorio();
    }

    public function listar()
    {
        $this->view('profissionais/lista', ['profissionais' => $this->repositorio->listar()]);
    }

    public function novo()
    {
        $this->view('profissionais/form', ['profissional' => new Profissional(), 'erros' => []]);
    }

    public function editar()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $profissional = $this->repositorio->buscar($id);

        if ($profissional === null) {
            $this->definirMensagem('erro', 'Profissional não encontrado.');
            $this->redirecionar('/profissionais');
        }

        $this->view('profissionais/form', ['profissional' => $profissional, 'erros' => []]);
    }

    public function salvar()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/profissionais');
        }

        $profissional = new Profissional();
        $profissional->id = (int) ($_POST['id'] ?? 0);
        $profissional->nome = trim($_POST['nome'] ?? '');
        $profissional->especialidade = trim($_POST['especialidade'] ?? '');
        $profissional->registro = trim($_POST['registro'] ?? '');
        $profissional->telefone = trim($_POST['telefone'] ?? '');

        $erros = $this->validar($profissional);

        if (empty($erros)) {
            try {
                $this->repositorio->salvar($profissional);
                $this->definirMensagem('sucesso', 'Profissional salvo com sucesso!');
                $this->redirecionar('/profissionais');
            } catch (PDOException $e) {
                $erros[] = 'Erro ao salvar o profissional no banco de dados.';
            }
        }

        $this->view('profissionais/form', ['profissional' => $profissional, 'erros' => $erros]);
    }

    public function excluir()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/profissionais');
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->repositorio->excluir($id);
            $this->definirMensagem('sucesso', 'Profissional excluído com sucesso!');
        } catch (PDOException $e) {
            $this->definirMensagem('erro', 'Não foi possível excluir: o profissional possui consultas registradas.');
        }

        $this->redirecionar('/profissionais');
    }

    private function validar($profissional)
    {
        $erros = [];

        if ($profissional->nome === '') {
            $erros[] = 'O nome é obrigatório.';
        } elseif (strlen($profissional->nome) > 100) {
            $erros[] = 'O nome deve ter no máximo 100 caracteres.';
        }

        if ($profissional->especialidade === '') {
            $erros[] = 'A especialidade é obrigatória.';
        } elseif (strlen($profissional->especialidade) > 100) {
            $erros[] = 'A especialidade deve ter no máximo 100 caracteres.';
        }

        if (strlen($profissional->registro) > 50) {
            $erros[] = 'O registro deve ter no máximo 50 caracteres.';
        }

        if (strlen($profissional->telefone) > 20) {
            $erros[] = 'O telefone deve ter no máximo 20 caracteres.';
        }

        return $erros;
    }
}
