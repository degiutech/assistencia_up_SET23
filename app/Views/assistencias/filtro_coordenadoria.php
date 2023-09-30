<div class="container">

    <div class="row mb-1">
        <div class="col-md-6">
            <h3 class="mt-4">Assistências por Coordenadoria</h3>

            <!-- <div class="row mb-2">

                <div class="col-auto cor-texto">
                    Total geral (<?= $dados['count_geral'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Não finalizadas (<?= $dados['count_geral_nao_finalizadas'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Finalizadas (<?= $dados['count_geral_finalizadas'] ?>)
                </div>

            </div> -->

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <?= Sessao::mensagem('assistencias') ?>

            <form action="<?= URL ?>/assistencias/filtro_coordenadoria" method="post">

                <div class="row">

                    <!-- coordenadorias -->
                    <div class="col-auto">
                        <label for="select_coordenadoria" class="form-label">Selecione a Coordenadoria</label>

                        <select class="form-select <?= $dados['select_coordenadoria_erro'] != '' ? 'is-invalid' : '' ?>" name="select_coordenadoria" aria-label="Default select example0">
                            <option value="0" selected>- -</option>

                            <?php if ($dados['coordenadorias'] != '') {
                                foreach ($dados['coordenadorias'] as $coord) { ?>
                                    <option value="<?= $coord['id'] ?>" <?= $dados['select_coordenadoria'] == $coord['id'] ? 'selected' : '' ?>><?= $coord['nome'] ?></option>;
                            <?php }
                            } ?>

                        </select>
                        <div class="invalid-feedback">
                            <?= $dados['select_coordenadoria_erro'] ?>
                        </div>
                    </div>

                    <!-- tipo de registro -->
                    <div class="col-auto">

                        <label for="tipo_registro" class="form-label">Tipo de registro</label>

                        <select class="form-select <?= $dados['tipo_registro_erro'] != '' ? 'is-invalid' : '' ?>" name="tipo_registro" aria-label="Default select example">
                            <option value="0" selected>- -</option>

                            <option value="assistencia" <?= $dados['tipo_registro'] == 'assistencia' ? 'selected' : ''; ?>>Primeiro registro</option>
                            <option value="update" <?= $dados['tipo_registro'] == 'update' ? 'selected' : ''; ?>>Atualização</option>
                        </select>

                        <div class="invalid-feedback">
                            <?= $dados['tipo_registro_erro'] ?>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                            <button type="button" onclick="toggle_divs('data')" class="btn btn-outline-primary">Data</button>
                            <button type="button" onclick="toggle_divs('mes_ano')" class="btn btn-outline-primary">Mês/Ano</button>
                            <button type="button" onclick="toggle_divs('periodo')" class="btn btn-outline-primary">Período</button>
                        </div>
                    </div>

                    <!-- data -->
                    <div class="divs col-auto" id="div_data" style="display: none;">
                        <label for="por_data" class="form-label">Por data</label>
                        <input type="date" name="por_data" value="<?= $dados['por_data'] ?>" id="input_por_data" class="form-control <?= $dados['por_data_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['por_data_erro'] ?>
                        </div>
                    </div>

                    <!-- mês/ano -->
                    <div class="divs col-auto" id="div_mes_ano" style="display: none;">
                        <label for="mes_ano" class="form-label">Por mês/ano</label>
                        <div class="row">

                            <div class="col-auto">
                                <select class="form-select <?= $dados['select_mes_erro'] != '' ? 'is-invalid' : '' ?>" id="select_mes" name="select_mes" aria-label="Default select example">

                                    <option value="<?= $dados['select_mes'] ?>">Mês</option>

                                    <?php foreach ($dados['meses'] as $mes) { ?>

                                        <option value="<?= $mes['int'] ?>" <?= $dados['select_mes'] == $mes['int'] ? 'selected' : ''; ?>><?= $mes['string'] ?></option>

                                    <?php } ?>

                                </select>
                                <div class="invalid-feedback">
                                    <?= $dados['select_mes_erro'] ?>
                                </div>
                            </div>

                            <div class="col-auto">
                                <select class="form-select <?= $dados['select_ano_erro'] != '' ? 'is-invalid' : '' ?>" id="select_ano" name="select_ano" aria-label="Default select example">
                                    <option value="<?= $dados['select_ano'] ?>">Ano</option>
                                    <?php foreach ($dados['anos'] as $ano) { ?>

                                        <option value="<?= $ano ?>" <?= $dados['select_ano'] == $ano ? 'selected' : ''; ?>><?= $ano ?></option>

                                    <?php } ?>

                                </select>
                                <div class="invalid-feedback">
                                    <?= $dados['select_ano_erro'] ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- período -->
                    <div class="divs row col-auto" id="div_periodo" style="display: none;">

                        <div class="col-auto">
                            <label for="dt_inicial" class="form-label">Data de Início</label>
                            <input type="date" value="<?= $dados['dt_inicial'] ?>" class="form-control <?= $dados['dt_inicial_erro'] != '' ? 'is-invalid' : '' ?>" id="dt_inicial" name="dt_inicial">

                            <div class="invalid-feedback">
                                <?= $dados['dt_inicial_erro'] ?>
                            </div>

                        </div>

                        <div class="col-auto">

                            <label for="dt_final" class="form-label">Data Final</label>
                            <input type="date" value="<?= $dados['dt_final'] ?>" class="form-control <?= $dados['dt_final_erro'] != '' ? 'is-invalid' : '' ?>" id="dt_final" name="dt_final">

                            <div class="invalid-feedback">
                                <?= $dados['dt_final_erro'] ?>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="input_datas" id="input_datas" value="<?= $dados['input_datas'] ?>">

                    <!-- botão buscar -->
                    <div class="col-auto" id="div_btn_buscar" style="display: none;">
                        <label for="btn_submit" class="form-label" style="visibility: hidden;">.</label>
                        <input type="submit" class="btn btn-info form-control" value="Buscar" name="btn_submit">
                    </div>

                </div>
            </form>
        </div>

    </div>

    <!-- Assistencias -->
    <?php if (isset($dados['assistencias'])) {
        $updates = '';
    ?>
        <div class="card mt-3 mb-3">
            <div class="card-body">

                <b class="cor-texto">Coordenadoria: <?= $dados['nome_coordenadoria'] ?></b><br>

                <b><?= $dados['titulo'] ?></b>

                <?php
                $count_assistencias = 0;
                if ($dados['assistencias'] != '') {
                    $count_assistencias = count($dados['assistencias']);
                }
                ?>
                <div>Assistências: <?= $count_assistencias ?></div>
                <div>Não finalizadas: <?= $dados['nao_finalizadas'] ?></div>
                <div class="mb-3">Finalizadas: <?= $dados['finalizadas'] ?></div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <?php if (isset($dados['assistencias']) && $dados['assistencias'] != '') {

                    $array_ups = [];

                    foreach ($dados['assistencias'] as $ass) {

                        $dt = date_create($ass['date_at']);
                        $data = date_format($dt, 'd/m/Y');

                ?>

                        <div class="card mb-3 meu_hover">
                            <div class="card-body">

                                <?= Sessao::mensagem('assistencia' . $ass['id']) ?>

                                <div>Assistido(a): <?= $ass['nome_cidadao'] ?></div>
                                <div>Descrição: <?= $ass['descricao'] ?> - <?= $ass['descricao_complemento'] ?></div>
                                <div>Data: <?= $data ?></div>
                                <div>Status atual: <?= $ass['status_atual'] ?></div>

                                <?php if ($ass['status_atual'] == 'Iniciada') {
                                    echo 'Não há atualizações';
                                } else {
                                    echo '<div>Última atualização: ' . $ass['ultima_atualizacao'] . '</div>';
                                    echo '<div>Descrição da última atualização: ' . $ass['desc_ultima_atualizacao'] . '</div>';
                                    echo '<div>Número de atualizações: ' . count($ass['updates']) - 1 . '</div>';
                                } ?>

                                <!-- <div>Coordenadoria: <?= $ass['nome_coordenadoria'] ?></div> -->

                                <div class="mt-2">

                                    <!-- <a href="<?= URL ?>/cidadao/cidadao/<?= $ass['id_cidadao'] ?>" class="btn btn-outline-primary btn-sm">Info Cidadão</a> -->

                                    <?php if ($ass['status_atual'] != 'Finalizada') { ?>
                                        <button onclick="modal_finalizar('<?= $ass['id'] ?>')" class="btn btn-outline-dark btn-sm">Finalizar</button>
                                        <button onclick="modal_atualizar('<?= $ass['id'] ?>')" class="btn btn-outline-secondary btn-sm">Atualizar</button>
                                    <?php } ?>

                                    <button onclick="modal_historico('<?= $ass['id'] ?>')" class="btn btn-outline-success btn-sm">Histórico</button>

                                </div>

                                Histórico


                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Data e hora</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Atualizada por</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php $updates = $dados['updates'][$ass['id']];

                                        for ($i = 0; $i < count($updates); $i++) { 
                                            
                                            $dt = date_create($updates[$i]['updated_at']);
                                            $data_hora = date_format($dt, 'd/m/Y H:i');
                                            
                                            ?>

                                            <tr>
                                                <td><?= $data_hora ?></td>
                                                <td><?= $updates[$i]['status_compl_updated'] ?></td>
                                                <td><?= $updates[$i]['status_updated'] ?></td>
                                                <td><?= $updates[$i]['name_updated_by'] ?></td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>





                                <!-- inputs para modais -->
                                <input type="hidden" id="id_assistencia_<?= $ass['id'] ?>" value="<?= $ass['id'] ?>">
                                <input type="hidden" id="descricao<?= $ass['id'] ?>" value="<?= $ass['descricao'] ?>">
                                <input type="hidden" id="descricao_complemento<?= $ass['id'] ?>" value="<?= $ass['descricao_complemento'] ?>">
                                <input type="hidden" id="nome_cidadao<?= $ass['id'] ?>" value="<?= $ass['nome_cidadao'] ?>">
                                <input type="hidden" id="id_cidadao<?= $ass['id'] ?>" value="<?= $ass['id_cidadao'] ?>">
                                <input type="hidden" id="desc_ass<?= $ass['id'] ?>" value="<?= $ass['descricao'] ?>">
                                <input type="hidden" id="id_coordenadoria<?= $ass['id'] ?>" value="<?= $ass['id_coordenadoria'] ?>">
                                <input type="hidden" id="nome_coordenadoria<?= $ass['id'] ?>" value="<?= $ass['nome_coordenadoria'] ?>">

                                <input type="hidden" id="status_atual<?= $ass['id'] ?>" value="<?= $ass['status_atual'] ?>">
                                <input type="hidden" id="status_complemento<?= $ass['id'] ?>" value="<?= $ass['status_complemento'] ?>">

                                <input type="hidden" id="sus<?= $ass['id'] ?>" value="<?= $ass['sus'] ?>">

                                <input type="hidden" id="desc_juridica<?= $ass['id'] ?>" value="<?= $ass['desc_juridica'] ?>">
                                <input type="hidden" id="num_proc_juridica<?= $ass['id'] ?>" value="<?= $ass['num_proc_juridica'] ?>">

                                <input type="hidden" id="name_created_ass<?= $ass['id'] ?>" value="<?= $ass['name_created_by'] ?>">

                                <input type="hidden" id="data_hora1<?= $ass['id'] ?>" value="<?= $data . ' ' . $ass['hora'] . ' h' ?>">
                                <input type="hidden" id="data_hora_up<?= $ass['id'] ?>" value="<?= $ass['ultima_atualizacao'] . ' ' . $ass['hora_ultima_atualizacao'] . ' h' ?>">

                            </div>

                        </div>

                <?php }

                    // $updates = json_encode($array_ups);
                    // echo json_encode($array_ups);
                } else {
                    echo 'Nenhum registro encontrado';
                }



                ?>

            </div>
        </div>

    <?php  } ?>

</div>


    <!-- MODAL ATUALIZAR -->
    <button type="button" id="btn_modal_atualizar" class="btn btn-primary modal-lg" data-bs-toggle="modal" data-bs-target="#modal_atualizar" style="display: none;">
        modal atualizar
    </button>

    <div class="modal fade" id="modal_atualizar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <?= include 'modal_atualizar.php' ?>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FINALIZAR -->
    <!-- Button trigger modal -->
    <button type="button" id="btn_modal_finalizar" class="btn btn-primary modal-lg" data-bs-toggle="modal" data-bs-target="#modal_finalizar" style="display: none;">
        modal finalizar
    </button>

    <div class="modal fade" id="modal_finalizar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <?= include 'modal_finalizar.php' ?>

                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
            </div>
        </div>
    </div>

    <!-- MODAL CIDADAO -->
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary modal-lg" data-bs-toggle="modal" data-bs-target="#modal_cidadao" style="display: none;">
        Launch demo modal
    </button>

    <div class="modal fade" id="modal_cidadao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        let input_datas = '<?= $dados['input_datas'] ?>'

        if (input_datas == "data") {
            $("#div_data").show()
            $("#div_btn_buscar").show()
        }
        if (input_datas == "mes_ano") {
            $("#div_mes_ano").show()
            $("#div_btn_buscar").show()
        }
        if (input_datas == "periodo") {
            $("#div_periodo").show()
            $("#div_btn_buscar").show()
        }

        function toggle_divs(tipo) {
            $(".divs").hide()
            $("#input_datas").val("nenhum")
            $("#div_btn_buscar").show()

            if (tipo == "data") {
                $("#div_data").show()
                $("#input_datas").val("data")
            }
            if (tipo == "mes_ano") {
                $("#div_mes_ano").show()
                $("#input_datas").val("mes_ano")
            }
            if (tipo == "periodo") {
                $("#div_periodo").show()
                $("#input_datas").val("periodo")
            }
        }

        function modal_atualizar(id_assistencia) {
            $("#btn_modal_atualizar").click()
            $("#status_atual_modal").val($("#status_atual" + id_assistencia).val())
            $("#span_status_atual_modal").text($("#status_atual" + id_assistencia).val())
            $("#id_cidadao_modal").val($("#id_cidadao" + id_assistencia).val())
            $("#nome_cidadao_modal").val($("#nome_cidadao" + id_assistencia).val())
            $("#span_nome_cidadao_modal").text($("#nome_cidadao" + id_assistencia).val())
            $("#id_assistencia_modal").val(id_assistencia)
            $("#span_descricao_modal").text($("#desc_ass" + id_assistencia).val())
            $("#sus_modal").val($("#sus" + id_assistencia).val())
            $("#desc_juridica_modal").val($("#desc_juridica" + id_assistencia).val())
            $("#num_proc_juridica_modal").val($("#num_proc_juridica" + id_assistencia).val())
        }

        function modal_finalizar(id_assistencia) {
            $("#btn_modal_finalizar").click()
            $("#id_cidadao_modal").val($("#id_cidadao" + id_assistencia).val())
            $("#id_assistencia_modal").val(id_assistencia)
            $("#nome_cidadao_modal").val($("#nome_cidadao" + id_assistencia).val())
            $("#span_nome_cidadao_modal").text($("#nome_cidadao" + id_assistencia).val())
            $("#span_desc_ass_modal").text($("#desc_ass" + id_assistencia).val())
            $("#desc_ass_modal").val($("#desc_ass" + id_assistencia).val())
            $("#id_coordenadoria_modal").val($("#id_coordenadoria" + id_assistencia).val())
            $("#nome_coordenadoria_modal").val($("#nome_coordenadoria" + id_assistencia).val())
        }
    </script>