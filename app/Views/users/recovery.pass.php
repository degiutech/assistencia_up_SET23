<?php

if (isset($_GET['key'])): 
$key = filter_input(INPUT_GET, 'key');
endif;

?>

<div class="col-xl-6 col-md-6 mx-auto p-5">

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5>Recuperação e/ou registro de senha.</h5>
        </div>
        <div class="card-body">
            <?= Sessao::mensagem('user') ?>
            <?= Sessao::mensagem('email') ?>
            <p class="card-text"><small class="text-muted">Informe seus dados para registrar sua senha</small></p>

            <form method="post" name="login" action="<?= URL ?>/users/recovery_pass">

            <?php if (isset($key)): ?>
                <input type="hidden" name="key" id="key" value="<?=$key?>">
            <?php else: ?>
                <input type="hidden" name="key" id="key"  value="<?=$dados['key']?>">
            <?php endif; ?>

                <div class="mb-3">
                    <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                    <input type="text" name="email" id="email" value="<?= $dados['email'] ?>"
                        class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['email_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>"
                        class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['pass_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm-pass">Confirme a Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="confirm-pass" id="confirm-pass" value="<?= $dados['confirm-pass'] ?>"
                        class="form-control <?= $dados['confirm-pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['confirm-pass_erro'] ?>
                    </div>
                </div>


                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" class="btn btn-info btn-block" value="Enviar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>