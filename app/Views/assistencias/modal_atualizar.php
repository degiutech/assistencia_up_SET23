<div class="container col-md-10">

    <div class="col-md-6">
        <h3>Atualizar Assistência</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <?php Sessao::mensagem('assistencia') ?>

            <form method="post" name="registrar" action="<?= URL ?>/assistencias/atualizar_modal">

                <input type="hidden" name="id_cidadao_modal" id="id_cidadao_modal">
                <input type="hidden" name="id_assistencia_modal" id="id_assistencia_modal">
                <input type="hidden" name="status_atual_modal" id="status_atual_modal">

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
                <h6>Assistência: <span id="span_descricao_modal"></span></h6>
                <h6>Status atual: <span id="span_status_atual_modal"></span></h6>

                <hr>

                <div class="row mt-3">
                    <!-- Status -->

                    <div class="col-md-6">

                        <label for="novo_status" class="form-label">Atualizar Status <sup class="text-danger">*</sup></label>

                        <select name="novo_status_modal" id="novo_status_modal" class="form-select">

                            <option value="Selecione">Selecione</option>
                            <option value="Em andamento" <?= ($dados['status_atual'] == 'Em andamento' || $dados['status_atual'] == 'Em_andamento') ? 'selected' : '' ?>>Em andamento</option>
                            <option value="Aguardando" <?= ($dados['status_atual'] == 'Aguardando') ? 'selected' : '' ?>>Aguardando</option>
                            <option value="Outro" <?= ($dados['status_atual'] == 'Outro') ? 'selected' : '' ?>>Outro</option>
                            <?php if ($dados['status_atual'] == 'Iniciada') {
                                echo '<option value="Iniciada" selected>Iniciada</option>';
                            } ?>
                        </select>

                    </div>

                    <!-- Breve descrição do status -->
                    <div class="col-md-6">

                        <label for="status_complemento" class="form-label">Complementações</label>

                        <button type="button" class="btn btn-light bg-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Ex. Se status AGUARDANDO: Resultado de exames sai na segunda, dia 28.">
                            <i class="bi bi-question-circle col-auto"></i>
                        </button>

                        <textarea name="status_complemento_modal" id="status_complemento_modal" class="form-control" cols="20" rows="3" maxlength="100" required placeholder="máx. 100 caracteres"><?= $dados['status_complemento'] ?></textarea>

                    </div>

                </div>

                <!-- Processo jurídico -->
                <?php if ($dados['id_coordenadoria'] == ID_JURIDICA) { ?>
                    <div id="div_coordenadoria_juridica">
                        <label class="mt-2">Processo Jurídico</label>
                        <div class="row" style="margin-top: -15px;">

                            <!-- Descrição -->
                            <div class="col-md-6 mt-4">
                                <input type="text" class="form-control" name="desc_juridica_modal" id="desc_juridica_modal" maxlength="45" placeholder="Descrição - ex. Primeira Vara da...">
                            </div>

                            <!-- Número do processo -->
                            <div class="col-md-4 mt-4">
                                <input type="text" class="procJuridico form-control" name="num_proc_juridica_modal" id="num_proc_juridica_modal" placeholder="Número Processo Jurídico">
                            </div>

                        </div>

                    </div>
                <?php } ?>

                <!-- Cartão SUS -->
                <?php if ($dados['id_coordenadoria'] == ID_SAUDE) { ?>
                    <div id="div_cartao_sus">

                        <label for="sus_modal">Cartão SUS </label>
                        <input type="text" name="sus_modal" id="sus_modal" class="sus form-control" minlength="18" maxlength="18" placeholder="somente números">

                    </div>
                <?php } ?>

                <!-- Botões -->
                <div class="mb-3 mt-4">
                    <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Atualizar">
                </div>

                <sup class="text-danger">*</sup> <small>Obrigatórios</small>

            </form>

        </div>

    </div>

</div>