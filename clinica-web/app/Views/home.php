<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<h1 class="mb-4">Painel</h1>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center"><div class="card-body">
            <h2><?= $dados['totalPacientes'] ?></h2><p class="mb-2">Pacientes</p>
            <a href="<?= Url::para('/pacientes') ?>" class="btn btn-sm btn-outline-primary">Ver pacientes</a>
        </div></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center"><div class="card-body">
            <h2><?= $dados['totalProfissionais'] ?></h2><p class="mb-2">Profissionais</p>
            <a href="<?= Url::para('/profissionais') ?>" class="btn btn-sm btn-outline-primary">Ver profissionais</a>
        </div></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center"><div class="card-body">
            <h2><?= $dados['totalConsultas'] ?></h2><p class="mb-2">Consultas</p>
            <a href="<?= Url::para('/consultas') ?>" class="btn btn-sm btn-outline-primary">Ver consultas</a>
        </div></div>
    </div>
</div>

<h4>Próximas consultas</h4>
<?php if (empty($dados['proximas'])): ?>
    <p class="text-muted">Nenhuma consulta futura agendada.</p>
<?php else: ?>
    <div class="table-responsive">
    <table class="table table-striped bg-white">
        <thead><tr><th>Data/Hora</th><th>Paciente</th><th>Profissional</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($dados['proximas'] as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c->dataHoraFormatada()) ?></td>
                <td><?= htmlspecialchars($c->paciente_nome) ?></td>
                <td><?= htmlspecialchars($c->profissional_nome) ?></td>
                <td><span class="badge bg-<?= $c->corStatus() ?>"><?= htmlspecialchars($c->statusFormatado()) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
