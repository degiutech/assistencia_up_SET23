<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">

    <?php if ($_SESSION['user']['acesso'] == 'Coordenadoria') { ?>

        <h3>Cadastro de Assessor(a)</h3>

    <?php } else { ?>

        <h3>Cadastro de Operador do Sistema</h3>
        <?php if ($_SESSION['user']['acesso'] == 'Administração') { ?>
            <small style="color:#696969">Cadastro de Administradores, Supervisores, Coordenadores e Assessores</small>
        <?php }
        if ($_SESSION['user']['acesso'] == 'Supervisão') { ?>
            <small style="color:#696969">Cadastro de Coordenadores e Assessores</small>
        <?php }
        if ($_SESSION['user']['acesso'] == 'Coordenadoria') { ?>
            <small style="color:#696969">Cadastro de Assessores</small>
        <?php } ?>

    <?php } ?>


    <div class="card">

        <div class="card-body">

            <!-- <p class="card-text"><small>Preencha o formulário para fazer seu registro.</small></p> -->

            <form method="post" name="registrar" action="<?= URL ?>/users/create">

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
                        <label for="celular">Celular:</label>
                        <input type="tel" name="celular" id="celular" value="<?= $dados['celular'] ?>" maxlength="50" class="celular form-control <?= $dados['celular_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['celular_erro'] ?>
                        </div>
                    </div>

                </div>

                <!-- Botões -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="javascript:window.history.back()" class="btn btn-outline-secondary btn-block">Cancelar</a>
                            <input type="reset" class="btn btn-outline-info btn-block" value="Limpar">
                            <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Continuar">
                        </div>
                        <div class="col-md-6">
                            <!-- <a href="<?= URL ?>/users/login">Já é registrado? Faça o login</a> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>