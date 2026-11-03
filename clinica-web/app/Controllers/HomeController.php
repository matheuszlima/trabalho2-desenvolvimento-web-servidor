<?php

class HomeController extends ControllerProtegido
{
    public function index()
    {
        $pacientes = new PacienteRepositorio();
        $profissionais = new ProfissionalRepositorio();
        $consultas = new ConsultaRepositorio();

        $this->view('home', [
            'totalPacientes'     => $pacientes->contar(),
            'totalProfissionais' => $profissionais->contar(),
            'totalConsultas'     => $consultas->contar(),
            'proximas'           => $consultas->proximas(),
        ]);
    }
}
