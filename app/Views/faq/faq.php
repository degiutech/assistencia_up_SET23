<div class="container">

    <div class="row">

        <div class="col-md-6">
            <h3 class="mt-5">Dúvidas Frequentes</h3>
        </div>

        <div class="d-flex justify-content-end align-items-end col-md-6 mb-1">
            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
            <?php } ?>
        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="col-md-6 mb-3">

                <?= Sessao::mensagem('faq') ?>

                <form action="<?= URL ?>/faq/faq" method="post" name="form_faq">
                    <label for="pergunta" class="form-label">Digite sua dúvida aqui</label>
                    <div><small>Por favor, antes de enviar, verifique abaixo se já não há perguntas relacionadas.</small></div>
                    <textarea class="form-control" id="pergunta" name="pergunta" rows="3"></textarea>
                    <input type="submit" class="btn btn-secondary mt-1" value="Enviar">
                </form>

            </div>

        </div>

    </div>

    <div class="card mt-3">

        <div class="card-body">

            <section>

                <?php if (isset($dados['perguntas'])) {

                    foreach ($dados['perguntas'] as $pergunta) { ?>

                        <div class="col-md-2">
                            <b><?= $pergunta['nome_user'] ?></b>
                            <?php
                            if ($pergunta['resposta'] != '') {
                                echo '<code>Respondida</code>';
                            }
                            ?>
                        </div>

                        <details>
                            <summary>
                                <?= $pergunta['pergunta'] ?>
                            </summary>
                            <p style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">
                                R. <?= $pergunta['resposta'] ?>
                            </p>
                        </details>
                        <hr>

                <?php }
                }
                if (!$dados['perguntas']) {
                    echo 'NÃO HÁ PERGUNTAS REGISTRADAS PARA ESTE ACESSO!';
                }
                ?>

            </section>

        </div>
    </div>
</div>