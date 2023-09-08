<div class="container d-flex justify-content-center">

    <div class="col-md-4">

        <h3 class="cor-texto mt-5 mb-2"><?= $dados['nome_operador'] ?></h3>

        <div class="card">

            <div class="card-header">
                <h5>Alterar senha de Operador</h5>
            </div>

            <div class="card-body">

                <form action="<?= URL ?>/operadores/update_pass_op/<?= $dados['id_operador'] ?>" method="post">

                <input type="hidden" value="<?= $dados['id_operador'] ?>" name="id_operador">
                <input type="hidden" value="<?= $dados['nome_operador'] ?>" name="nome_operador">

                    <div class="mb-3">
                        <label for="pass">Nova senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['pass_erro'] ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_pass">Confirme a nova senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_pass" id="confirm_pass" value="<?= $dados['confirm_pass'] ?>" class="form-control <?= $dados['confirm_pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['confirm_pass_erro'] ?>
                        </div>
                    </div>

                    <div class="mb-2">
                        <button type="submit" class="btn btn-info">Enviar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>