<div class="container">

    <?php

    echo 'Id coordenadoria: ' . $dados['id_coordenadoria'] . '<br>';
    echo 'Select coordenadoria: ' .  $dados['select_coordenadoria'] . '<br>';
    echo 'Tipo de registro:' . $dados['tipo_registro'] . '<br>';
    echo 'Por data: ' . $dados['por_data'] . '<br>';
    echo 'select mês: ' . $dados['select_mes'] . '<br>';
    echo 'select ano: ' . $dados['select_ano'] . '<br>';
    echo 'dt inicial: ' . $dados['dt_inicial'] . '<br>';
    echo 'dt final: ' . $dados['dt_final'] . '<br>';
    echo 'input datas: ' . $dados['input_datas'] . '<br>';

    ?>

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