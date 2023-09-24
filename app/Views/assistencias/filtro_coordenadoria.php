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

    <!-- REGISTROS -->
    <!-- RELATÓRIO -->
    <div class="card" id="div_relatorio" style="display: none;">
        <div class="card-body">

            <div class="row">
                <div class="col-10">
                    <h6><?= $dados['titulo_relatorio'] ?><span><i class="bi bi-file-arrow-down-fill text-dark" onclick="Javascript:window.print()" title="Salvar relatório" style="font-size: 2rem; cursor: pointer;"></i></span></h6>

                    Assistências: <?= $count_assistencias ?>

                    <?php if ($dados['titulo_botao'] != 'não finalizadas' && $dados['titulo_botao'] != 'finalizadas') { ?>
                        <div>Não Finalizadas: <?= $count_nao_finalizadas; ?></div>
                        <div>Finalizadas: <?= $count_finalizadas; ?></div>
                    <?php } ?>

                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-success mb-2" onclick="toggle_relatorio()"><i class="bi bi-arrow-left"></i> Voltar</button>
                </div>
            </div>

            <table class="table table-sm table-bordered mt-3" style="width: 100%; position: relative;">
                <thead>
                    <tr>
                        <th scope="col" style="width: 15%;">1º registro</th>
                        <th scope="col" style="width: 20%;">Descrição 1º registro</th>
                        <th scope="col" style="width: 15%;">Última atualização</th>
                        <th scope="col" style="width: 20%;">Desc. última atualização</th>
                        <th scope="col" style="width: 15%;">Status Atual</th>
                        <th scope="col" style="width: 15%;">Histórico</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if ($dados['assistencias']) {

                        $array_existe2 = [];

                        foreach ($dados['assistencias'] as $ass) {
                            if (!in_array($ass['id_primeiro_registro'], $array_existe2)) {
                                $array_existe2[] = $ass['id_primeiro_registro'];
                    ?>

                                <tr>
                                    <td scope="row" style="width: 15%;"><?= $ass['data_primeiro_registro'] ?></td>
                                    <td style="width: 20%;"><?= $ass['descricao'] ?> - <?= $ass['desc_comp_primeiro_reg'] ?></td>
                                    <td style="width: 15%;"><?= $ass['data'] ?></td>
                                    <td style="width: 20%;"><?= $ass['status_compl_updated'] ?></td>
                                    <td style="width: 15%;"><?= $ass['status_assist'] ?></td>
                                    <td style="width: 15%;">
                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $ass['id_primeiro_registro'] ?>">
                                            <i class="bi bi-list-task" title="Histórico"></i>
                                        </a>
                                    </td>
                                </tr>

                    <?php }
                        }
                    } ?>

                </tbody>
            </table>
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
    </script>