<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">


    <h3>Alteração de Cadastro de Operador(a) do Sistema</h3>


    <div class="card">

        <div class="card-body">

            <?= SessaoDg::mensagem('assistencia') ?>

            <form method="post" name="update" action="<?= URL ?>/dgassistencia/update_operador/<?= $dados['id'] ?>">

            <input type="hidden" value="<?= $dados['id'] ?>" name="id">
            <input type="hidden" value="<?= $dados['email_original'] ?>" name="email_original">

                <div class="row">

                    <div class="mb-3 col-md-6">
                        <label for="nome">Nome: <sup class="text-danger">*</sup></label>
                        <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" minlength="3" maxlength="40" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                        <input type="email" name="email" id="email" value="<?= $dados['email'] ?>" maxlength="50" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>" required>
                        <div class="invalid-feedback">
                            <?= $dados['email_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="pass">Senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['pass_erro'] ?>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="pass_confirm">Confirme a senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="pass_confirm" id="pass_confirm" value="<?= $dados['pass_confirm'] ?>" class="form-control <?= $dados['pass_confirm_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['pass_confirm_erro'] ?>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="javascript:window.history.back()" class="btn btn-outline-secondary btn-block">Cancelar</a>
                            <input type="reset" class="btn btn-outline-info btn-block" value="Limpar">
                            <input type="submit" id="btn-register" class="btn btn-primary btn-block" value="Salvar">
                        </div>
                        <div class="col-md-6">
                            <!-- <a href="<?= URL ?>/users/login">Já  registrado? Faça o login</a> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>