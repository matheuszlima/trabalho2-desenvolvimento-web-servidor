<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Consultas</h1>
    <a href="<?= Url::para('/consultas/novo') ?>" class="btn btn-primary">Nova Consulta</a>
</div>

<form method="GET" action="<?= Url::para('/consultas') ?>" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <?php foreach (Consulta::$statusDisponiveis as $valor => $texto): ?>
                <option value="<?= $valor ?>" <?= $dados['status'] === $valor ? 'selected' : '' ?>><?= $texto ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto"><button type="submit" class="btn btn-outline-primary">Filtrar</button></div>
</form>

<?php if (empty($dados['consultas'])): ?>
    <div class="alert alert-info">Nenhuma consulta encontrada.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped bg-white">
    <thead><tr><th>Data/Hora</th><th>Paciente</th><th>Profissional</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($dados['consultas'] as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c->dataHoraFormatada()) ?></td>
            <td><?= htmlspecialchars($c->paciente_nome) ?></td>
            <td><?= htmlspecialchars($c->profissional_nome) ?></td>
            <td><span class="badge bg-<?= $c->corStatus() ?>"><?= htmlspecialchars($c->statusFormatado()) ?></span></td>
            <td>
                <a href="<?= Url::para('/consultas/editar') ?>?id=<?= (int) $c->id ?>" class="btn btn-sm btn-warning">Editar</a>
                <form method="POST" action="<?= Url::para('/consultas/excluir') ?>" class="d-inline"
                      onsubmit="return confirm('Excluir esta consulta?');">
                    <?= Csrf::campo() ?>
                    <input type="hidden" name="id" value="<?= (int) $c->id ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
