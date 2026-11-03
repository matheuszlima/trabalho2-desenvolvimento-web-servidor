<?php
$profissional = $dados['profissional'];
$erros = $dados['erros'];
require RAIZ . '/app/Views/layout/cabecalho.php';
?>

<h1 class="mb-3"><?= empty($profissional->id) ? 'Novo' : 'Editar' ?> Profissional</h1>

<?php if (!empty($erros)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($erros as $erro): ?><li><?= htmlspecialchars($erro) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="<?= Url::para('/profissionais/salvar') ?>" class="card card-body">
    <?= Csrf::campo() ?>
    <input type="hidden" name="id" value="<?= (int) $profissional->id ?>">

    <div class="mb-3">
        <label class="form-label">Nome *</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($profissional->nome ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Especialidade *</label>
        <input type="text" name="especialidade" class="form-control" value="<?= htmlspecialchars($profissional->especialidade ?? '') ?>">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Registro (CRM etc.)</label>
            <input type="text" name="registro" class="form-control" value="<?= htmlspecialchars($profissional->registro ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($profissional->telefone ?? '') ?>">
        </div>
    </div>

    <div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= Url::para('/profissionais') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
