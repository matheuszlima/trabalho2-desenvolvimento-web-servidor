<?php require RAIZ . '/app/Views/layout/cabecalho.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Sistema Clínica</h3>

                <?php if (!empty($dados['erro'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($dados['erro']) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= Url::para('/login') ?>">
                    <?= Csrf::campo() ?>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($dados['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require RAIZ . '/app/Views/layout/rodape.php'; ?>
