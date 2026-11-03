<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Clínica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php if ($usuarioLogado !== null): ?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::para('/') ?>">Sistema Clínica</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= Url::para('/pacientes') ?>">Pacientes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::para('/profissionais') ?>">Profissionais</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::para('/consultas') ?>">Consultas</a></li>
            </ul>
            <span class="navbar-text me-3">Olá, <?= htmlspecialchars($usuarioLogado) ?></span>
            <a class="btn btn-sm btn-outline-light" href="<?= Url::para('/logout') ?>">Sair</a>
        </div>
    </div>
</nav>
<?php endif; ?>
<div class="container">
<?php if ($mensagem !== null): ?>
    <div class="alert alert-<?= $mensagem['tipo'] === 'sucesso' ? 'success' : 'danger' ?> alert-dismissible fade show">
        <?= htmlspecialchars($mensagem['texto']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
