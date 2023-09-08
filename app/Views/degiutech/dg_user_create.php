<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">

    <h3>Cadastro de Usuário DegiuTech</h3>

    <div class="card">

        <div class="card-body">

            <!-- <p class="card-text"><small>Preencha o formulário para fazer seu registro.</small></p> -->

            <form method="post" name="registrar" action="<?= URL ?>/dguser/create">

                <?= SessaoDg::mensagem('dg_user') ?>

                <div class="mb-3">
                    <label for="nome">Nome: <sup class="text-danger">*</sup></label>
                    <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" maxlength="40" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['nome_erro'] ?>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-7 mb-3">
                        <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                        <input type="email" name="email" id="email" value="<?= $dados['email'] ?>" maxlength="50" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['email_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="celular">Celular: <sup class="text-danger">*</sup></label>
                        <input type="tel" name="celular" id="celular" value="<?= $dados['celular'] ?>" maxlength="50" class="celular form-control <?= $dados['celular_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['celular_erro'] ?>
                        </div>
                    </div>

                </div>

                <!-- Senhas -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['pass_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="confirm-pass">Confirme a senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_pass" id="confirm_pass" value="<?= $dados['confirm_pass'] ?>" class="form-control <?= $dados['confirm_pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['confirm_pass_erro'] ?>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="javascript:window.history.back()" class="btn btn-outline-secondary btn-block">Cancelar</a>
                            <input type="reset" class="btn btn-outline-info btn-block" value="Limpar">
                            <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Enviar">
                        </div>
                        <div class="col-md-6">
                            <a href="<?= URL ?>/dguser/login_dg">Já é registrado? Faça o login</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>