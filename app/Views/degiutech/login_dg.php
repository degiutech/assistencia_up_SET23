<div class="d-flex justify-content-center col-12" style="position: absolute; opacity: 0.5">
    <div style="font-size: 50px; color: #2F4F4F">

        <div class="d-none d-md-none d-lg-block">DEGIU TECHNOLOGY</div>
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
            <h3>LOGIN DEGIUTECH</h3>
        </div>
        <div class="card-body">
            <?= SessaoDg::mensagem('dg_user') ?>
            <?= SessaoDg::mensagem('msg') ?>
            <p class="card-text"><small class="text-muted">Digite seus dados para fazer o login</small></p>

            <form method="post" name="login" action="<?= URL ?>/dguser/login_dg">

                <div class="mb-3">
                    <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                    <input type="text" name="email" id="email" value="<?= $dados['email'] ?>" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['email_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['pass_erro'] ?>
                    </div>
                </div>


                <div class="mb-3">
                    
                    <div class="col-md-auto">
                        <a href="<?= URL ?>/users/login_email" class="btn btn-outline-info">Cancelar</a>
                        <input type="submit" class="btn btn-info btn-block" value="Entrar">
                    </div>
                    <div class="mt-3">
                        <a href="<?= URL ?>/dguser/create" class="col-12">Registro</a>
                    </div>
                    <div>
                        <a href="<?= URL ?>" class="col-12">Login Fabinho</a>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("email").focus()
</script>