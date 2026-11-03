<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Profissionais</h1>
    <a href="<?= Url::para('/profissionais/novo') ?>" class="btn btn-primary">Novo Profissional</a>
</div>

<?php if (empty($dados['profissionais'])): ?>
    <div class="alert alert-info">Nenhum profissional cadastrado ainda.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped bg-white">
    <thead><tr><th>Nome</th><th>Especialidade</th><th>Registro</th><th>Telefone</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($dados['profissionais'] as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p->nome) ?></td>
            <td><?= htmlspecialchars($p->especialidade) ?></td>
            <td><?= htmlspecialchars($p->registro ?? '') ?></td>
            <td><?= htmlspecialchars($p->telefone ?? '') ?></td>
            <td>
                <a href="<?= Url::para('/profissionais/editar') ?>?id=<?= (int) $p->id ?>" class="btn btn-sm btn-warning">Editar</a>
                <form method="POST" action="<?= Url::para('/profissionais/excluir') ?>" class="d-inline"
                      onsubmit="return confirm('Excluir este profissional?');">
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
