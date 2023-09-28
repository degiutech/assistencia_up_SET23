<div class="container">

    <div class="col-md-6">
        <h3>Finalizar Assistência</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <?php Sessao::mensagem('assistencia') ?>


            <form method="post" name="registrar" action="<?= URL ?>/assistencias/finalizar_modal">

                <input type="hidden" name="id_cidadao" id="id_cidadao_modal">
                <input type="hidden" name="id_assistencia" id="id_assistencia_modal">
                <input type="hidden" name="descricao_assistencia" id="desc_ass_modal">
                <input type="hidden" name="nome_cidadao" id="nome_cidadao_modal">
                <input type="hidden" name="id_coordenadoria" id="id_coordenadoria_modal">
                <input type="hidden" name="nome_coordenadoria" id="nome_coordenadoria_modal">

                <input type="hidden" name="input_datas" value="<?= $dados['input_datas'] ?>">

                <input type="hidden" name="select_coordenadoria_modal" value="<?= $dados['select_coordenadoria'] ?>">
                <input type="hidden" name="tipo_registro_modal" value="<?= $dados['tipo_registro'] ?>">

                <!-- datas -->
                <input type="hidden" name="por_data_modal" value="<?= $dados['por_data'] ?>">
                <input type="hidden" name="select_mes_modal" value="<?= $dados['select_mes'] ?>">
                <input type="hidden" name="select_ano_modal" value="<?= $dados['select_ano'] ?>">
                <input type="hidden" name="dt_inicial_modal" value="<?= $dados['dt_inicial'] ?>">
                <input type="hidden" name="dt_final_modal" value="<?= $dados['dt_final'] ?>">


                <h5 class="cor-texto"><span>Nome do Cidadão: <b id="span_nome_cidadao_modal"></b></span></h5>
                <h6>Assistência: <span id="span_desc_ass_modal"></span></h6>

                <hr>


                <!-- Breve descrição do status -->
                <div class="col-md-6">

                    <label for="status_complemento" class="form-label">Ações Realizadas</label>

                    <button type="button" class="btn btn-light bg-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Ex. Conseguimos os remédios solicitados.">
                        <i class="bi bi-question-circle col-auto"></i>
                    </button>

                    <textarea name="status_complemento" id="status_complemento" class="form-control" cols="20" rows="3" maxlength="100" placeholder="máx. 100 caracteres" required></textarea>

                </div>

                <!-- Botões -->
                <div class="mb-3 mt-4">

                    <p style="color: orange">Deseja mesmo finalizar esta Assistência?</p>

                    <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Finalizar Assistência">
                </div>

                <sup class="text-danger">*</sup> <small>Obrigatórios</small>

            </form>

        </div>

    </div>

    <script>
        $("#status_complemento").val('<?= $dados['status_complemento'] ?>')
    </script>