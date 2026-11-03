<?php
$consulta = $dados['consulta'];
$erros = $dados['erros'];
require RAIZ . '/app/Views/layout/cabecalho.php';
?>

<h1 class="mb-3"><?= empty($consulta->id) ? 'Nova' : 'Editar' ?> Consulta</h1>

<?php if (!empty($erros)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($erros as $erro): ?><li><?= htmlspecialchars($erro) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if (empty($dados['pacientes']) || empty($dados['profissionais'])): ?>
<div class="alert alert-warning">
    Para agendar uma consulta é preciso ter pelo menos um paciente e um profissional cadastrados.
</div>
<?php endif; ?>

<form method="POST" action="<?= Url::para('/consultas/salvar') ?>" class="card card-body">
    <?= Csrf::campo() ?>
    <input type="hidden" name="id" value="<?= (int) $consulta->id ?>">

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Paciente *</label>
            <select name="paciente_id" class="form-select">
                <option value="">Selecione...</option>
                <?php foreach ($dados['pacientes'] as $p): ?>
                    <option value="<?= (int) $p->id ?>" <?= (int) $consulta->paciente_id === (int) $p->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p->nome) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Profissional *</label>
            <select name="profissional_id" class="form-select">
                <option value="">Selecione...</option>
                <?php foreach ($dados['profissionais'] as $p): ?>
                    <option value="<?= (int) $p->id ?>" <?= (int) $consulta->profissional_id === (int) $p->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p->nome) ?> - <?= htmlspecialchars($p->especialidade) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Data e hora *</label>
            <input type="datetime-local" name="data_hora" class="form-control" value="<?= htmlspecialchars($consulta->dataHoraParaFormulario()) ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (Consulta::$statusDisponiveis as $valor => $texto): ?>
                    <option value="<?= $valor ?>" <?= $consulta->status === $valor ? 'selected' : '' ?>><?= $texto ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" rows="3" class="form-control"><?= htmlspecialchars($consulta->observacoes ?? '') ?></textarea>
    </div>

    <div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= Url::para('/consultas') ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
