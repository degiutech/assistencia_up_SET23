<div class="container d-flex justify-content-center">

    <div class="col-md-4">

        <h3 class="cor-texto mt-5 mb-2"><?= $dados['nome'] ?></h3>

        <div class="card">

            <div class="card-header">
                <h5>Trocar minha senha</h5>
            </div>

            <div class="card-body">

                <form action="update_minha_senha" method="post">

                    <!-- <div style="color: orange;"><small>Sua nova senha deve conter no mínimo 8 caracteres sendo:</small></div>
                    <div style="color: orange;"><small>Pelo menos uma letra maiúscula</small></div>
                    <div style="color: orange;"><small>Pelo menos uma letra minúscula</small></div>
                    <div style="color: orange;"><small>Pelo menos um caracter especial</small></div>
                    <div style="color: orange;" class="mb-3"><small>Pelo menos um número</small></div> -->

                    <div class="mb-3">
                        <label for="pass">Nova senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="pass" id="pass" value="<?= $dados['pass'] ?>" class="form-control <?= $dados['pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['pass_erro'] ?>
                        </div>

                        <!-- força da senha -->
                        <h6 id="forca-senha"></h6>

                        <div class="progress" id="div-barra" style="display: none">
                            <span class="progress-bar" role="progressbar" id="barra-forca" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_pass">Confirme a nova senha: <sup class="text-danger">*</sup></label>
                        <input type="password" name="confirm_pass" id="confirm_pass" value="<?= $dados['confirm_pass'] ?>" class="form-control <?= $dados['confirm_pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['confirm_pass_erro'] ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="atual_pass">Senha atual: <sup class="text-danger">*</sup></label>
                        <input type="password" name="atual_pass" id="atual_pass" value="<?= $dados['atual_pass'] ?>" class="form-control <?= $dados['atual_pass_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['atual_pass_erro'] ?>
                        </div>

                    </div>

                    <div class="mb-2">
                        <button type="submit" class="btn btn-info">Enviar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>