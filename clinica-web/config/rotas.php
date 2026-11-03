<?php
// Rotas do sistema: URL => [Controller, metodo]
// A URL e comparada exatamente com o caminho acessado (ex.: /pacientes/novo).
return [
    "/"                       => ["HomeController", "index"],

    "/login"                  => ["AuthController", "login"],
    "/logout"                 => ["AuthController", "logout"],

    "/pacientes"              => ["PacienteController", "listar"],
    "/pacientes/novo"         => ["PacienteController", "novo"],
    "/pacientes/editar"       => ["PacienteController", "editar"],
    "/pacientes/salvar"       => ["PacienteController", "salvar"],
    "/pacientes/excluir"      => ["PacienteController", "excluir"],

    "/profissionais"          => ["ProfissionalController", "listar"],
    "/profissionais/novo"     => ["ProfissionalController", "novo"],
    "/profissionais/editar"   => ["ProfissionalController", "editar"],
    "/profissionais/salvar"   => ["ProfissionalController", "salvar"],
    "/profissionais/excluir"  => ["ProfissionalController", "excluir"],

    "/consultas"              => ["ConsultaController", "listar"],
    "/consultas/novo"         => ["ConsultaController", "novo"],
    "/consultas/editar"       => ["ConsultaController", "editar"],
    "/consultas/salvar"       => ["ConsultaController", "salvar"],
    "/consultas/excluir"      => ["ConsultaController", "excluir"],
];
