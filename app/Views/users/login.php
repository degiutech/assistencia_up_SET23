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
        <div class="card-body">
            <?= Sessao::mensagem('user') ?>
            <p class="card-text"><small class="text-muted">Digite sua senha para fazer o login</small></p>

            <form method="post" name="login" action="<?= URL ?>/users/login">

                <div class="mb-3">
                    <input type="hidden" name="email" id="email" value="<?= $dados['email'] ?>" class="form-control">

                </div>

                <div class="mb-3">
                    <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['pass_erro'] ?>
                    </div>
                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-auto">
                            <a href="<?= URL ?>/users/login_email" class="btn btn-outline-info">Cancelar</a>
                            <input type="submit" class="btn btn-info btn-block" value="Entrar">
                        </div>
                        <!-- <div class="col-md-5">
                            <a href="<?= URL ?>/users/create" class="col-12">Não está registrado? Registre-se</a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= URL ?>/emails/password_recovery" class="col-12">Esqueci a senha</a>
                        </div> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("pass").focus()
</script>