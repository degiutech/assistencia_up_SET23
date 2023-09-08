<div class="d-flex justify-content-center col-12" style="position: absolute; opacity: 0.5">
    <div style="font-size: 50px; color: #2F4F4F">

        <div class="d-none d-md-none d-lg-block">Deputado Estadual Fabinho</div>
        <div class="d-flex justify-content-center">
            <h4 class="d-none d-md-none d-lg-block">Gestão de Assistência ao Cidadão</h4>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center col-12 d-block d-sm-none" style="opacity: 0.5">
    <h4>Deputado Estadual Fabinho</h4>
</div>

<div class="d-flex justify-content-center col-12 d-block d-sm-none" style="opacity: 0.5">
    <h6>Gestão de Assistência ao Cidadão</h6>
</div>

<div class="d-flex align-items-center justify-content-center" style="height: 100vh; width: 100vw;">

    <div class="card col-md-3">
        <div class="card-header bg-secondary text-white">
            <h3>Login</h3>
        </div>
        <div class="card-body" style="z-index: 10;">
            <?= Sessao::mensagem('user'); ?>
            <p class="card-text"><small class="text-muted">Informe seu e-mail para fazer o login</small></p>

            <form method="post" name="login" action="<?= URL ?>/users/login_email">

                <div class="mb-3">
                    <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                    <input type="text" name="email" id="email" value="<?= $dados['email'] ?>" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['email_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="submit" class="btn btn-info btn-block" value="Continuar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("email").focus()
</script>