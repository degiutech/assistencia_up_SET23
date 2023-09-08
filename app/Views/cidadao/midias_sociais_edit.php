<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">

    <h3>Mídias Sociais</h3>

    <div class="card">
        <div class="card-header">
            <h5>Cidadão - Atualização de Mídias Sociais</h5>
        </div>
        <div class="card-body">

            <?= Sessao::mensagem('cidadao') ?>

            <!-- <p class="card-text"><small>Preencha o formulário para fazer seu registro.</small></p> -->

            <form method="post" name="registrar" action="<?= URL ?>/cidadao/midias_sociais_edit">

                <h5 class="cor-texto"><?= $dados['nome'] ?></h5>

                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="facebook"><span><i class="bi bi-facebook"></i> </span>Facebook</label>
                        <input type="text" value="https://facebook.com/" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label></label>
                        <input type="text" name="facebook" id="facebook" value="<?= $dados['facebook'] ?>" maxlength="40" class="form-control <?= $dados['facebook_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['facebook_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="twitter"><span><i class="bi bi-twitter"></i> </span>Twitter</label>
                        <input type="text" value="https://twitter.com/" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="twitter"></label>
                        <input type="text" name="twitter" id="twitter" value="<?= $dados['twitter'] ?>" maxlength="40" class="form-control <?= $dados['twitter_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['twitter_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="instagram"><span><i class="bi bi-instagram"></i> </span>Instagram</label>
                        <input type="text" value="https://instagram.com/" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="instagram"></label>
                        <input type="text" name="instagram" id="instagram" value="<?= $dados['instagram'] ?>" maxlength="40" class="form-control <?= $dados['instagram_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['instagram_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="telegram"><span><i class="bi bi-telegram"></i> </span>Telegram</label>
                        <input type="text" value="https://telegram.com/" class="form-control" readonly>
                        <div class="invalid-feedback">
                            <?= $dados['telegram_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telegram"></label>
                        <input type="text" name="telegram" id="telegram" value="<?= $dados['telegram'] ?>" maxlength="40" class="form-control <?= $dados['telegram_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['telegram_erro'] ?>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="skype"><span><i class="bi bi-skype"></i> </span>Skype</label>
                        <input type="text" value="https://skipe.com/" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="skype"></label>
                        <input type="text" name="skype" id="skype" value="<?= $dados['skype'] ?>" maxlength="40" class="form-control <?= $dados['skype_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['skype_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-auto mb-3">
                        <label for="tiktok"><span><i class="bi bi-tiktok"></i> </span>Tik Tok</label>
                        <input type="text" value="https://tiktok.com/" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tiktok"></label>
                        <input type="text" name="tiktok" id="tiktok" value="<?= $dados['tiktok'] ?>" maxlength="40" class="form-control <?= $dados['tiktok_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['tiktok_erro'] ?>
                        </div>
                    </div>

                </div>



                <!-- Botões -->
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= URL ?>/cidadao/cancelar_edicao_cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-outline-warning">Cancelar</a>
                            <input type="submit" class="btn btn-outline-secondary btn-block" value="Pular">
                            <input type="submit" class="btn btn-info btn-block" value="Concluir">
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