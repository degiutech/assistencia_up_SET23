<div class="container">

    <div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

        <div class="row mb-1">
            <div class="col-md-6">
                <h3>Nova Assistência</h3>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <?php if (isset($dados['home'])) { ?>
                    <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="margin-right: 10px;">HOME</a>
                <?php } ?>
                <a href="<?= URL ?>/cidadao/cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-info">Info Cidadão</a>
            </div>
        </div>

        <div class="card">


            <div class="card-body">

                <?= Sessao::mensagem('assistencia') ?>

                <!-- <p class="card-text"><small>Preencha o formulário para fazer seu registro.</small></p> -->

                <form method="post" name="registrar" action="<?= URL ?>/diversos/create_assistencia/<?= $dados['id_cidadao'] ?>">

                    <input type="hidden" name="id_cidadao" value="<?= $dados['id_cidadao'] ?>">
                    <input type="hidden" name="nome_cidadao" value="<?= $dados['nome_cidadao'] ?>">
                    <input type="hidden" name="nome_coordenadoria_selecionada" value="<?= $dados['nome_coordenadoria_selecionada'] ?>">

                    <input type="hidden" name="id_created_by" value="<?= $dados['id_created_by'] ?>">
                    <input type="hidden" name="name_created_by" value="<?= $dados['name_created_by'] ?>">


                    <h5 class="cor-texto"><span>Nome do Cidadão: <b><?= $dados['nome_cidadao'] ?></b></span></h5>

                    <div class="row">

                        <!-- Breve descrição -->
                        <div class="col-md-6 mt-4">
                            <label for="descricao" class="form-label">Breve descrição da Assistência <sup class="text-danger">*</sup></label>

                            <button type="button" class="btn btn-light bg-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Ex. Internação no Pronto Socorro de Várzea Grande">
                                <i class="bi bi-question-circle col-auto"></i>
                            </button>

                            <input type="text" name="descricao" id="descricao" value="<?= $dados['descricao'] ?>" class="form-control <?= $dados['descricao_erro'] ? 'is-invalid' : '' ?>" maxlength="60" minlength="4" placeholder="max. 60 caracteres" required>

                            <div class="invalid-feedback">
                                <?= $dados['descricao_erro'] ?>
                            </div>
                        </div>

                        <!-- Complementares -->
                        <div class="col-md-6 mt-4">
                            <label for="descricao_complemento" class="form-label">Informações complementares</label>

                            <button type="button" class="btn btn-light bg-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Ex. Caiu de moto no dia 24/05 e estava sem atendimento por falta de vaga.">
                                <i class="bi bi-question-circle col-auto"></i>
                            </button>

                            <textarea name="descricao_complemento" id="descricao_complemento" class="form-control" cols="20" rows="3" maxlength="100" placeholder="máx. 100 caracteres" required></textarea>

                            <div class="invalid-feedback">
                                <?= $dados['descricao_complemento_erro'] ?>
                            </div>

                        </div>

                    </div>

                    <!-- Data e hora -->
                    <div class="row">

                        <div class="col-md-3 mt-4">
                            <label for="date_at" class="form-label">Data</label>
                            <input type="date" class="form-control" value="<?= $dados['date_at'] ?>" name="date_at" required>
                        </div>

                        <div class="col-md-3 mb-3 mt-4">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" value="<?= $dados['hora'] ?>" required>
                        </div>

                        <!-- Coordenadorias -->
                        <div class="col-md-6 mt-4">

                            <label for="id_coordenadoria" class="form-label">Coordenadoria <sup class="text-danger">*</sup></label>

                            <select class="form-select <?= $dados['id_coordenadoria_erro'] ? 'is-invalid' : '' ?>" name="id_coordenadoria" id="coordenadoria_select" aria-label="Selecionar uma Coordenadoria">

                                <option value="0" selected>Selecione</option>

                                <?php foreach ($dados['coordenadorias'] as $coord) { ?>

                                    <!-- <option value="<?= $coord['id'] ?>" <?= ($coord['nome'] == $coord['id']) ? 'selected' : '' ?>><?= $coord['nome'] ?></option> -->
                                    <option value="<?= $coord['id'] ?>" <?= ($dados['user_id_coordenadoria'] == $coord['id'] || $dados['id_coordenadoria'] == $coord['id']) ? 'selected' : '' ?>><?= $coord['nome'] ?></option>

                                <?php } ?>

                            </select>

                            <div class="invalid-feedback">
                                <?= $dados['id_coordenadoria_erro'] ?>
                            </div>

                        </div>
                    </div>

                    <!-- Qual coordenadoria? -->
                    <input type="hidden" name="qual_coordenadoria" id="qual_coordenadoria">

                    <!-- Processo jurídico -->
                    <div id="div_coordenadoria_juridica" class="mais_coordenadorias" style="display: none;">
                        <label class="mt-2">Processo Jurídico</label>
                        <div class="row" style="margin-top: -15px;">

                            <!-- Descrição -->
                            <div class="col-md-6 mt-4">
                                <input type="text" class="form-control" name="descricao_juridica" maxlength="45" placeholder="Descrição - ex. Primeira Vara da...">
                            </div>

                            <!-- Número do processo -->
                            <div class="col-md-4 mt-4">
                                <input type="text" class="procJuridico form-control <?= $dados['num_proc_juridica_erro'] != '' ? 'is-invalid' : '' ?>" name="num_proc_juridica" value="<?= $dados['num_proc_juridica'] ?>" placeholder="Número Processo Jurídico">
                                <div class="invalid-feedback">
                                    <?= $dados['num_proc_juridica_erro'] ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- SUS -->
                    <div id="div_coordenadoria_saude" style="display: none;" class="mais_coordenadorias">

                        <div class="row" style="margin-top: -15px;">

                            <!-- Número do cartão -->
                            <div class="col-md-4 mt-4">
                                <label for="sus">Cartão SUS </label>
                                <input type="text" name="sus" id="sus" value="<?= $dados['sus'] ?>" class="sus form-control <?= $dados['sus_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="18" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="somente números">
                                <div class="invalid-feedback">
                                    <?= $dados['sus_erro'] ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Botões -->
                    <div class="mb-3 mt-4">
                        <!-- <a href="javascript:window.history.go(-1)" class="btn btn-outline-warning">Cancelar</a> -->

                        <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-outline-warning">Cancelar</a>
                        <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Enviar">
                    </div>

                    <sup class="text-danger">*</sup> <small>Obrigatórios</small>

                </form>

            </div>

        </div>
    </div>
</div>

<script>
    $("#status_complemento").val('<?= $dados['status_complemento'] ?>')
    $("#descricao_complemento").val('<?= $dados['descricao_complemento'] ?>')

    //Coordenadorias
    //ATENÇÃO: MUDAR AS VARIÁVEIS CONFORME O BD EM PRODUÇÃO
    let id_coord_saude = '<?= ID_SAUDE ?>'
    let id_coord_juridica = '<?= ID_JURIDICA ?>'

    //ao selecionar uma coordenadoria
    $("#coordenadoria_select").change(() => {

        $(".mais_coordenadorias").hide()

        //Saúde
        if ($("#coordenadoria_select").val() == id_coord_saude) {
            $("#div_coordenadoria_saude").show()
            $("#qual_coordenadoria").val("saude")
        }

        //Jurídica
        if ($("#coordenadoria_select").val() == id_coord_juridica) {
            $("#div_coordenadoria_juridica").show()
            $("#qual_coordenadoria").val("juridica")
        }
    })

    //exibir div_coordenadoria_saude
    if ($("#coordenadoria_select").val() == id_coord_saude) {
        $(".mais_coordenadorias").hide()
        $("#div_coordenadoria_saude").show()
        $("#qual_coordenadoria").val("saude")
    }

    //exibir div_coordenadoria_juridica
    if ($("#coordenadoria_select").val() == id_coord_juridica) {
        $(".mais_coordenadorias").hide()
        $("#div_coordenadoria_juridica").show()
        $("#qual_coordenadoria").val("juridica")
    }
</script>