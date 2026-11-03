<?php
$paciente = $dados['paciente'];
$erros = $dados['erros'];
require RAIZ . '/app/Views/layout/cabecalho.php';
?>

<h1 class="mb-3"><?= empty($paciente->id) ? 'Novo' : 'Editar' ?> Paciente</h1>

<?php if (!empty($erros)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($erros as $erro): ?><li><?= htmlspecialchars($erro) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="<?= Url::para('/pacientes/salvar') ?>" class="card card-body">
    <?= Csrf::campo() ?>
    <input type="hidden" name="id" value="<?= (int) $paciente->id ?>">

    <div class="mb-3">
        <label class="form-label">Nome *</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($paciente->nome ?? '') ?>">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CPF * (somente números ou com pontos e traço)</label>
            <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($paciente->cpf ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($paciente->telefone ?? '') ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">E-mail</label>
            <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($paciente->email ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" value="<?= htmlspecialchars($paciente->data_nascimento ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($paciente->endereco ?? '') ?>">
    </div>

    <div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= Url::para('/pacientes') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
