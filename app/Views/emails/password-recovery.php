<div class="col-xl-6 col-md-6 mx-auto p-5" style="margin-bottom: 300px">

    <div class="card">

        <div class="card-header bg-secondary text-white">
            <h3>Recuperação de Senha</h3>
        </div>

        <div class="card-body">

            <?= Sessao::mensagem('email') ?>
            
            <p class="card-text"><small class="text-muted">Informe seu e-mail cadastrado no sistema. Você receberá um
                    link para recuperar ou registrar sua senha.</small></p>

            <form method="post" name="email-pass-recovery" action="<?= URL ?>/emails/password_recovery">

                <div class="mb-3">
                    <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                    <input type="text" name="email" id="email" value="<?= $dados['email'] ?>"
                        class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['email_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-row">

                        <a href="<?=URL?>" class="btn btn-outline-info">Cancelar</a>

                        <input type="submit" class="btn btn-info btn-block" value="Enviar">

                    </div>
                </div>

            </form>

        </div>

    </div>

</div>