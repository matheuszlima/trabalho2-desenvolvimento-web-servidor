<?php

use Carbon\Carbon;

class ConsultaController extends ControllerProtegido
{
    private $repositorio;
    private $pacientes;
    private $profissionais;

    public function __construct()
    {
        parent::__construct();
        $this->repositorio = new ConsultaRepositorio();
        $this->pacientes = new PacienteRepositorio();
        $this->profissionais = new ProfissionalRepositorio();
    }

    public function listar()
    {
        // Filtro opcional por status (?status=agendada)
        $status = $_GET['status'] ?? '';
        if (!array_key_exists($status, Consulta::$statusDisponiveis)) {
            $status = '';
        }

        $this->view('consultas/lista', [
            'consultas' => $this->repositorio->listar($status),
            'status'    => $status,
        ]);
    }

    public function novo()
    {
        $consulta = new Consulta();
        $consulta->status = 'agendada';
        $this->exibirFormulario($consulta, []);
    }

    public function editar()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $consulta = $this->repositorio->buscar($id);

        if ($consulta === null) {
            $this->definirMensagem('erro', 'Consulta não encontrada.');
            $this->redirecionar('/consultas');
        }

        $this->exibirFormulario($consulta, []);
    }

    public function salvar()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/consultas');
        }

        $consulta = new Consulta();
        $consulta->id = (int) ($_POST['id'] ?? 0);
        $consulta->paciente_id = (int) ($_POST['paciente_id'] ?? 0);
        $consulta->profissional_id = (int) ($_POST['profissional_id'] ?? 0);
        $consulta->data_hora = trim($_POST['data_hora'] ?? '');
        $consulta->status = $_POST['status'] ?? 'agendada';
        $consulta->observacoes = trim($_POST['observacoes'] ?? '');

        $erros = $this->validar($consulta);

        if (empty($erros)) {
            // Formato aceito pelo MySQL
            $consulta->data_hora = Carbon::parse($consulta->data_hora)->format('Y-m-d H:i:s');

            try {
                $this->repositorio->salvar($consulta);
                $this->definirMensagem('sucesso', 'Consulta salva com sucesso!');
                $this->redirecionar('/consultas');
            } catch (PDOException $e) {
                $erros[] = 'Erro ao salvar a consulta no banco de dados.';
            }
        }

        $this->exibirFormulario($consulta, $erros);
    }

    public function excluir()
    {
        if (!$this->requisicaoPost()) {
            $this->redirecionar('/consultas');
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->repositorio->excluir($id);
            $this->definirMensagem('sucesso', 'Consulta excluída com sucesso!');
        } catch (PDOException $e) {
            $this->definirMensagem('erro', 'Não foi possível excluir a consulta.');
        }

        $this->redirecionar('/consultas');
    }

    private function exibirFormulario($consulta, $erros)
    {
        $this->view('consultas/form', [
            'consulta'      => $consulta,
            'erros'         => $erros,
            'pacientes'     => $this->pacientes->listar(),
            'profissionais' => $this->profissionais->listar(),
        ]);
    }

    private function validar($consulta)
    {
        $erros = [];

        if ($this->pacientes->buscar($consulta->paciente_id) === null) {
            $erros[] = 'Selecione um paciente.';
        }

        if ($this->profissionais->buscar($consulta->profissional_id) === null) {
            $erros[] = 'Selecione um profissional.';
        }

        if ($consulta->data_hora === '') {
            $erros[] = 'Informe a data e a hora da consulta.';
        } elseif (!$this->dataValida($consulta->data_hora, 'Y-m-d\TH:i')) {
            $erros[] = 'Data e hora inválidas.';
        }

        if (!array_key_exists($consulta->status, Consulta::$statusDisponiveis)) {
            $erros[] = 'Status inválido.';
        }

        if (strlen($consulta->observacoes) > 500) {
            $erros[] = 'As observações devem ter no máximo 500 caracteres.';
        }

        // Regra de negocio: o profissional nao pode ter duas consultas no mesmo horario
        if (empty($erros) && $consulta->status !== 'cancelada') {
            $dataHora = Carbon::parse($consulta->data_hora)->format('Y-m-d H:i:s');
            if ($this->repositorio->existeConflito($consulta->profissional_id, $dataHora, $consulta->id)) {
                $erros[] = 'Este profissional já possui uma consulta neste horário.';
            }
        }

        return $erros;
    }
}
