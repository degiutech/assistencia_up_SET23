<?= Sessao::mensagem('coordenadoria') ?>

<div class="container">

    <div class="row mb-1">
        <div class="col-md-6">
            <div class="row">
                <div class="col-auto">
                    <?php if ($_SESSION['user']['acesso'] != 'Coordenadoria') { ?>
                        <h3>Coordenadoria</h3>
                    <?php } ?>
                </div>
                <div class="col-auto">
                    <!-- <h3 class="cor-texto"><b><?= mb_strtoupper($dados['nome_coordenadoria']) ?></b></h3> -->
                    <h3 class="cor-texto"><b><?= $dados['nome_coordenadoria'] ?></b></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-end">
            <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary" style="margin-right: 5px;">Cidadãos</a>

            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
            <?php } ?>
        </div>
    </div>

    <!-- RESUMO -->
    <div class="card">
        <div class="card-body">
            <b>RESUMO</b>

            <table class="table">
                <thead>
                    <tr>

                        <th scope="col">Total Assistências</th>
                        <th scope="col">Cidadãos assistidos</th>
                        <th scope="col">Coordenadores</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <!-- Total de Assistências -->
                        <td>
                            <div><?= $dados['count_assistencias'] ?></div>
                        </td>

                        <!-- Total de Cidadão assistidos -->
                        <td>
                            <div><?= $dados['count_assistidos'] ?></div>
                        </td>

                        <!-- Coordenadores -->
                        <td>
                            <?php

                            foreach ($dados['coordenadores'] as $coordenador) {

                            ?>

                                <div><?= $coordenador['nome'] ?></div>


                            <?php };

                            ?>
                        </td>

                    </tr>

                </tbody>
            </table>


        </div>
    </div>

    <div class="card mb-3 mt-3">
        <div class="card-body">

            <b>ASSISTÊNCIAS</b>

            <hr>

            <div class="mb-3">


                <!-- botão recentes -->
                <a href="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/recentes" class="btn btn-outline-secondary">Recentes</a>


                <!-- busca por data -->
                <a href="javascript:por_data()" class="btn btn-outline-secondary" id="link_por_data">Por Data</a>


                <a href="javascript:por_mes_ano()" class="btn btn-outline-secondary">Mês/ano</a>

                <a href="javascript:por_periodo()" class="btn btn-outline-secondary">Período</a>

                <!-- <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary" style="float:right">Cidadãos</a>

                <?php if (isset($dados['home'])) { ?>
                    <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
                <?php } ?> -->

            </div>

            <!-- Assistências -->
            <div class="card">
                <div class="card-body">

                    <?= Sessao::mensagem('assistencias') ?>

                    <div><?= $dados['titulo'] ?></div>
                    <div><?= $dados['num_registros'] ?></div>
                    <div class="mb-3"><?= $dados['count_assistidos'] ?></div>




                    <?php if ($dados['assistencias'] != []) {
                        foreach ($dados['assistencias'] as $ass) { ?>

                            <div class="card mb-3 meu_hover">
                                <div class="card-body">
                                    <div>Assistido: <?= $ass['nome_cidadao'] ?></div>
                                    <div>Descrição: <?= $ass['descricao'] ?></div>
                                    <div>Data: <?= $ass['data'] ?></div>
                                    <div>Tipo: <?= $ass['tipo'] ?></div>
                                    <div>Cidadão assistido desde <?= $ass['data_primeiro_registro'] ?></div>
                                    <div>Coordenadoria: <?= $ass['nome_coordenadoria'] ?></div>

                                    <div class="mt-2">

                                        <a href="<?= URL ?>/cidadao/cidadao/<?= $ass['id_cidadao'] ?>" class="btn btn-outline-primary btn-sm">Info Cidadão</a>

                                        <?php if ($ass['tipo'] != 'Finalização') { ?>

                                            <a href="<?= URL ?>/admin/finalizar_assistencia/<?= $ass['id_primeiro_registro'] ?>" class="btn btn-outline-dark btn-sm">Finalizar</a>
                                            <a href="<?= URL ?>/admin/update_status_assistencia/<?= $ass['id_primeiro_registro'] ?>/<?= $ass['status_assist'] ?>" class="btn btn-outline-secondary btn-sm">Atualizar</a>
                                        <?php } ?>

                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $ass['id_primeiro_registro'] ?>" class="btn btn-outline-success btn-sm">Histórico</a>
                                    </div>

                                </div>

                            </div>

                    <?php }
                    } else {
                        echo 'Nenhum resultado encontrado';
                    } ?>

                </div>

            </div>

        </div>
    </div>

    <!-- MODAIS -->

    <!-- Por data -->
    <button type="button" id="btn_modal_data" style="display:none" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
        Launch demo modal
    </button>

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Selecione a data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Formulário -->
                <form action="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/data" method="post">
                    <div class="modal-body">
                        <input type="date" name="por_data" id="input_por_data" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a>
                        <button type="button" class="btn btn-primary" id="btn_por_data_prov">Buscar</button>
                        <input type="submit" id="btn_por_data" class="btn btn-primary" value="Buscar" style="display: none;">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Por mes/ano -->
    <button type="button" id="btn_modal_mes_ano" style="display:none" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
        Launch demo modal
    </button>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Selecione mês e ano.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Formulário -->
                <form action="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/mes_ano" method="post">

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="select_mes">Mês</label>
                                <select class="form-select" id="select_mes" name="mes" aria-label="Default select example">

                                    <?php foreach ($dados['meses'] as $mes) { ?>

                                        <option value="<?= $mes['int'] ?>"><?= $mes['string'] ?></option>

                                    <?php } ?>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="select_ano">Ano</label>
                                <select class="form-select" id="select_ano" name="ano" aria-label="Default select example">

                                    <?php foreach ($dados['anos'] as $ano) { ?>

                                        <option><?= $ano ?></option>

                                    <?php } ?>

                                </select>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a>
                        <input type="submit" id="btn_mes_ano" class="btn btn-primary" value="Buscar">
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Por período -->
    <button type="button" id="btn_modal_periodo" style="display:none" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3">
        Launch demo modal
    </button>

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Informe o período</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Formulário -->
                <form action="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/periodo" method="post">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dt_inicial" class="form-label">Data de Início</label>
                                <input type="date" class="form-control" id="dt_inicial" name="dt_inicial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dt_final" class="form-label">Data Final</label>
                                <input type="date" class="form-control" id="dt_final" name="dt_final">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a>
                        <input type="button" id="btn_periodo_provisorio" class="btn btn-primary" value="Buscar">
                        <input type="submit" id="btn_periodo" class="btn btn-primary" value="Buscar" style="display:none">
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        //POR DATA
        function por_data() {
            $("#btn_modal_data").click();
        }
        $("#btn_por_data_prov").on("click", () => {
            let por_data = $("#input_por_data").val()
            if (por_data.length < 10) {
                alert("É preciso informar a data completa!")
            } else {
                $("#btn_por_data").click()
            }
        })

        //POR MÊS/ANO
        function por_mes_ano() {
            $("#btn_modal_mes_ano").click();
        }

        function por_periodo() {
            $("#btn_modal_periodo").click();
            $("#btn_periodo_provisorio").on("click", () => {
                let dt_inicial = $("#dt_inicial").val()
                let dt_final = $("#dt_final").val()
                if (dt_inicial == "" || dt_final == "") {
                    alert("Informe corretamente a data inicial e a data final!")
                } else {
                    $("#btn_periodo").click()
                }
            })
        }
    </script>



</div>