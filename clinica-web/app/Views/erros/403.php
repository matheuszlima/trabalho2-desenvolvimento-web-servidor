<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<div class="text-center mt-5">
    <h1 class="display-4">403</h1>
    <p class="lead">Requisição inválida (token CSRF). Recarregue a página e tente novamente.</p>
    <a href="<?= Url::para('/') ?>" class="btn btn-primary">Voltar ao início</a>
</div>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
