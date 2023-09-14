<div class="container col-md-8">

    <div class="row">

        <div class="col-md-6">
            <h3>Atualizar Assistência</h3>
        </div>

        <div class="col-md-6 d-md-flex justify-content-end">
            <?php if (isset($dados['link_gerenciamento'])) { ?>
                <div>
                    <a href="<?= $dados['link_gerenciamento'] ?>" style="margin-right: 10px;" class="btn btn-outline-primary">Assistências </a>
                </div>
            <?php }
            if (isset($dados['home'])) { ?>
                <div>
                    <a href="<?= $dados['home'] ?>" style="margin-right: 10px;" class="btn btn-success">HOME </a>
                </div>
            <?php } ?>
            <div>
                <a href="<?= URL ?>/cidadao/cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-secondary">Cidadão</a>
            </div>
        </div>

    </div>


    <div class="card">
        <div class="card-body">
            <?php Sessao::mensagem('assistencia') ?>

            <?php if (isset($dados['status_atual']) && $dados['status_atual'] == 'Finalizada') { ?>

                <div class="alert alert-warning">ASSISTÊNCIA FINALIZADA</div>

                <a href="<?= URL ?>/assistencias/create/<?= $dados['id_cidadao'] ?>" class="btn btn-info btn-block">Nova Assistência</a>
                <a href="<?= URL ?>/assistencias/index" class="btn btn-info btn-block">Assistências</a>

            <?php } else {

                $status_ass = $dados['status_atual'];

                if ($dados['status_atual'] == 'Em andamento') {
                    $status_ass = 'Em_andamento';
                }

            ?>

                <form method="post" name="registrar" action="<?= URL ?>/assistencias/update_status/<?= $dados['id_assistencia'] ?>/<?= $status_ass ?>">

                    <input type="hidden" name="id_cidadao" value="<?= $dados['id_cidadao'] ?>">
                    <input type="hidden" name="id_assistencia" value="<?= $dados['id_assistencia'] ?>">
                    <input type="hidden" name="status_atual" value="<?= $dados['status_atual'] ?>">

                    <h5 class="cor-texto"><span>Nome do Cidadão: <b><?= $dados['nome_cidadao'] ?></b></span></h5>
                    <h6>Assistência: <span><?= $dados['descricao'] ?></span></h6>
                    <h6>Status atual: <span><?= $dados['status_atual'] ?></span></h6>

                    <hr>

                    <!-- Finalizar Assistência -->
                    <label for="finalizar" class="form-label">Finalizar Assistência <sup class="text-danger"></label>
                    <div>
                        <a href="<?= URL ?>/assistencias/finalizar/<?= $dados['id_assistencia'] ?>" class="btn btn-outline-warning btn-sm">Finalizar</a>
                    </div>

                    <div class="row mt-3">
                        <!-- Status -->

                        <div class="col-md-6">

                            <label for="novo_status" class="form-label">Atualizar Status <sup class="text-danger">*</sup></label>

                            <select name="novo_status" id="novo_status" class="form-select">

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

                            <textarea name="status_complemento" id="status_complemento" class="form-control" cols="20" rows="3" maxlength="100" placeholder="máx. 100 caracteres"><?= $dados['status_complemento'] ?></textarea>

                        </div>

                    </div>

                    <!-- Processo jurídico -->
                    <?php if ($dados['id_coordenadoria'] == ID_JURIDICA) { ?>
                        <div id="div_coordenadoria_juridica">
                            <label class="mt-2">Processo Jurídico</label>
                            <div class="row" style="margin-top: -15px;">

                                <!-- Descrição -->
                                <div class="col-md-6 mt-4">
                                    <input type="text" class="form-control" name="desc_juridica" value="<?= $dados['desc_juridica'] ?>" maxlength="45" placeholder="Descrição - ex. Primeira Vara da...">
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
                    <?php } ?>

                    <!-- Cartão SUS -->
                    <?php if ($dados['id_coordenadoria'] == ID_SAUDE) { ?>
                        <div id="div_cartao_sus">

                            <label for="sus">Cartão SUS </label>
                            <input type="text" name="sus" id="sus" value="<?= $dados['sus'] ?>" class="sus form-control <?= $dados['sus_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="18" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="somente números">
                            <div class="invalid-feedback">
                                <?= $dados['sus_erro'] ?>
                            </div>

                        </div>
                    <?php } ?>

                    <!-- Botões -->
                    <div class="mb-3 mt-4">
                        <!-- <a href="javascript:window.history.go(-1)" class="btn btn-outline-warning">Cancelar</a> -->

                        <a href="<?= $dados['home'] ?>" class="btn btn-outline-success">HOME</a>

                        <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Atualizar">
                    </div>

                    <sup class="text-danger">*</sup> <small>Obrigatórios</small>

                </form>

            <?php } ?>

        </div>

    </div>