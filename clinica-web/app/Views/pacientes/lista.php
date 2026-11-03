<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Pacientes</h1>
    <a href="<?= Url::para('/pacientes/novo') ?>" class="btn btn-primary">Novo Paciente</a>
</div>

<?php if (empty($dados['pacientes'])): ?>
    <div class="alert alert-info">Nenhum paciente cadastrado ainda.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped bg-white">
    <thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>Nascimento</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($dados['pacientes'] as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p->nome) ?></td>
            <td><?= htmlspecialchars($p->cpfFormatado()) ?></td>
            <td><?= htmlspecialchars($p->telefone ?? '') ?></td>
            <td><?= htmlspecialchars($p->dataNascimentoFormatada()) ?></td>
            <td>
                <a href="<?= Url::para('/pacientes/editar') ?>?id=<?= (int) $p->id ?>" class="btn btn-sm btn-warning">Editar</a>
                <form method="POST" action="<?= Url::para('/pacientes/excluir') ?>" class="d-inline"
                      onsubmit="return confirm('Excluir este paciente?');">
                    <?= Csrf::campo() ?>
                    <input type="hidden" name="id" value="<?= (int) $p->id ?>">
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
