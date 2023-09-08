<div class="col-xl-3 col-sm-6 col-md-10 mx-auto mt-5" style="margin-bottom: 300px">

    <?= Sessao::mensagem('user') ?>

    <div class="card">

        <div class="card-header bg-secondary text-white">
            <h3>Registro de senha</h3>
        </div>

        <div class="card-body">

            <form method="post" name="pass-register" action="<?= URL ?>/users/pass_register">

                <input type="hidden" name="id" id="id" value="<?= $dados['id'] ?>">


                <div class="mb-3">
                    <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['pass_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm-pass">Confirme a senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="confirm-pass" id="confirm-pass" value="<?= $dados['confirm-pass'] ?>" class="form-control <?= $dados['confirm-pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['confirm-pass_erro'] ?>
                    </div>
                </div>

                <div class="mb-2">
                    <button type="submit" class="btn btn-info">Registrar</button>
                </div>


            </form>

        </div>

    </div>

</div>