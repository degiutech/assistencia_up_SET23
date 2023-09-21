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

            <form action="busca_por_coordenadoria" method="post">

                <div class="row">

                    <div class="col-auto">
                        <label for="select_coordenadoria" class="form-label">Selecione a Coordenadoria</label>
                        <select class="form-select" name="select_coordenadoria" aria-label="Default select example">
                            <option value="0" selected>- -</option>

                            <?php if ($dados['coordenadorias'] != '') {
                                foreach ($dados['coordenadorias'] as $coord) {
                                    echo '<option value="' . $coord['id'] . '">' . $coord['nome'] . '</option>';
                                }
                            } ?>

                        </select>
                    </div>

                    <div class="col-auto">

                        <label for="tipo_registro" class="form-label">Tipo de registro</label>

                        <select class="form-select" name="tipo_registro" aria-label="Default select example">
                            <option value="0" selected>- -</option>

                            <option value="assistencia">Primeiro registro</option>
                            <option value="update">Atualização</option>
                        </select>
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
                        <input type="date" name="por_data" id="input_por_data" class="form-control">
                    </div>

                    <!-- mês/ano -->
                    <div class="divs col-auto" id="div_mes_ano" style="display: none;">
                        <label for="mes_ano" class="form-label">Por mês/ano</label>
                        <div class="row">

                            <div class="col-auto">
                                <select class="form-select" id="select_mes" name="mes" aria-label="Default select example">

                                    <option value="mes">Mês</option>

                                    <?php foreach ($dados['meses'] as $mes) { ?>

                                        <option value="<?= $mes['int'] ?>"><?= $mes['string'] ?></option>

                                    <?php } ?>

                                </select>
                            </div>

                            <div class="col-auto">
                                <select class="form-select" id="select_ano" name="ano" aria-label="Default select example">
                                    <option value="ano">Ano</option>
                                    <?php foreach ($dados['anos'] as $ano) { ?>

                                        <option><?= $ano ?></option>

                                    <?php } ?>

                                </select>
                            </div>

                        </div>

                    </div>

                    <!-- período -->
                    <div class="divs row col-auto" id="div_periodo" style="display: none;">
                        <div class="col-auto">
                            <label for="dt_inicial" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="dt_inicial" name="dt_inicial">
                        </div>
                        <div class="col-auto">
                            <label for="dt_final" class="form-label">Data Final</label>
                            <input type="date" class="form-control" id="dt_final" name="dt_final">
                        </div>
                    </div>

                    <!-- botão buscar -->
                    <div class="col-auto" id="div_btn_buscar" style="display: none;">
                        <label for="btn_submit" class="form-label" style="visibility: hidden;">.</label>
                        <input type="submit" class="btn btn-info form-control" value="Buscar" name="btn_submit">
                    </div>

                    <input type="hidden" name="input_datas" id="input_datas" value="nenhum">

                </div>
            </form>
        </div>

    </div>

    <script>
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