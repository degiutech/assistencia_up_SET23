<div class="container" style="width: 100vw;
height: 100vh;
display: flex;
flex-direction: row;
justify-content: center;
align-items: center;">

    <div class="box card" style="width: 400px; height: auto; background: #fff;">

        <div class="card-header">
            <div class="card-title">
                DEGIUTECH - Redefinindo senha
            </div>
        </div>

        <div class="card-body">

            <?= SessaoDg::mensagem('msg') ?>

            <form action="" method="post">

                <div class="mb-3">
                    <label for="senha_atual">Senha atual: <sup class="text-danger">*</sup></label>
                    <input type="password" name="senha_atual" id="senha_atual" value="<?= $dados['senha_atual'] ?>" class="form-control <?= $dados['senha_atual_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['senha_atual_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pass">Senha nova: <sup class="text-danger">*</sup></label>
                    <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['pass_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_pass">Confirme a Senha: <sup class="text-danger">*</sup></label>
                    <input type="password" name="confirm_pass" id="confirm_pass" value="<?= $dados['confirm_pass'] ?>" class="form-control <?= $dados['confirm_pass_erro'] != '' ? 'is-invalid' : '' ?>">
                    <div class="invalid-feedback">
                        <?= $dados['confirm_pass_erro'] ?>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="reset" class="btn btn-outline-secondary" value="Cancelar">
                    <input type="submit" class="btn btn-secondary" value="Redefinir">
                </div>

            </form>

        </div>

    </div>
</div>