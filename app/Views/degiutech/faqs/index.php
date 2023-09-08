<div class="container">
    <h4>Perguntas não respondidas</h4>

    <div class="card">
        <div class="card-body">

            <?= SessaoDg::mensagem('msg') ?>

            <section>

                <?php if (isset($dados['perguntas'])) {

                    if (count($dados['perguntas']) != 0) {

                        foreach ($dados['perguntas'] as $pergunta) { ?>

                            <div class="col-md-2">
                                <b><?= $pergunta['nome_user'] ?></b>
                            </div>

                            <details>
                                <summary>
                                    <?= $pergunta['pergunta'] ?>
                                </summary>
                                <p style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">
                                    R. <?= $pergunta['resposta'] ?>

                                <form action="<?= URL ?>/degiutech/responder" method="post">
                                    <input type="hidden" value="<?= $pergunta['id'] ?>" name="id">
                                    <textarea class="form-control" name="resposta" id="resposta<?= $pergunta['id'] ?>" maxlength="700" onkeyup="literar_btn(<?= $pergunta['id'] ?>)"></textarea>
                                    <button type="submit" class="btn btn-outline-primary mt-2" id="btn_responder<?= $pergunta['id'] ?>" disabled>Reponder</button>
                                </form>
                                </p>
                            </details>
                            <hr>

                <?php }
                    } else {
                        echo 'Todas as Perguntas estão respondidas!';
                    }
                } ?>

            </section>

        </div>
    </div>
</div>

<script>
    function literar_btn(id) {

        let resposta = $("#resposta" + id).val()

        if (resposta.length > 2) {
            $("#btn_responder" + id).attr("disabled", false)
        } else {
            $("#btn_responder" + id).attr("disabled", true)
        }
    }

    // $(document).ready(() => {

    // })
</script>