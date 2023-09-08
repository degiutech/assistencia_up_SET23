<?= Sessao::mensagem('coord') ?>

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


                        <th scope="col">Assistências não finalizadas</th>
                        <th scope="col">Assistências finalizadas</th>
                        <th scope="col">Total Assistências</th>
                        <th scope="col">Cidadãos assistidos</th>
                        <th scope="col">Coordenadores</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>


                        <!-- Total de Assistências não finalizadas -->
                        <td>
                            <div><?= $dados['count_ass_nao_finalizadas'] ?></div>
                        </td>

                        <!-- Total de Assisências finalizadas -->
                        <td>
                            <div><?= $dados['count_ass_finalizadas'] ?></div>
                        </td>

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

            <div class="mb-3 mt-3">

                <!-- botão recentes -->
                <?php if ($dados['titulo_botao'] == 'recentes') { ?>
                    <a href="" class="btn btn-outline-secondary disabled">Recentes</a>
                <?php } else { ?>
                    <a href="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/recentes" class="btn btn-outline-secondary">Recentes</a>
                <?php } ?>

                <!-- botão não finalizadas -->
                <?php if ($dados['titulo_botao'] == 'não finalizadas') { ?>
                    <a href="" class="btn btn-outline-secondary disabled">Não Finalizadas</a>
                <?php } else { ?>
                    <a href="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/nao_finalizadas" class="btn btn-outline-secondary">Não Finalizadas</a>
                <?php } ?>

                <!-- botão finalizadas -->
                <?php if ($dados['titulo_botao'] == 'finalizadas') { ?>
                    <a href="" class="btn btn-outline-secondary disabled">Finalizadas</a>
                <?php } else { ?>
                    <a href="<?= URL ?>/admin/coordenadoria/<?= $dados['id_coordenadoria'] ?>/finalizadas" class="btn btn-outline-secondary">Finalizadas</a>
                <?php } ?>

                <!-- busca por data -->
                <a href="javascript:por_data()" class="btn btn-outline-secondary" id="link_por_data">Por Data</a>


                <a href="javascript:por_mes_ano()" class="btn btn-outline-secondary">Mês/ano</a>
                <a href="javascript:por_periodo()" class="btn btn-outline-secondary">Período</a>

            </div>

            <hr>

            <?= $dados['titulo_consulta'] ?>
            <p>Registros: <?= $dados['num_registros'] ?></p>
            <p>Mostrando máximo: 200</p>

            <!-- Assistências -->
            <div class="accordion accordion-flush mt-3" id="accordionFlushExample">

                <?php if ($dados['assistencias'] && $dados['assistencias'] != '') {

                    foreach ($dados['assistencias'] as $ass) {

                        $cor = '';

                        if ($ass['status_assist'] == 'Em andamento' || $ass['status_assist'] == 'Aguardando') {
                            $cor = 'style="color:#FE642E"';
                        }
                        if ($ass['status_assist'] == 'Finalizada') {
                            $cor = 'style="color:#688A08"';
                        }

                        $status_assist = $ass['status_assist'];
                        if ($status_assist == 'Iniciada') {
                            $status_assist = 'Sem atualizações';
                        }


                        $dt = new DateTime($ass['date_at']);
                        $data_ass = $dt->format('d/m/Y');

                ?>

                        <div class="card mb-2">
                            <div class="card-bory">


                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="ass_flush<?= $ass['id'] ?>">
                                        <button class="accordion-button collapsed btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#ass_colapse<?= $ass['id'] ?>" aria-expanded="false" aria-controls="ass_colapse<?= $ass['id'] ?>">

                                            <h6 style="color: #8B4513;">

                                                <div style="color: #4F4F4F;"><?= $ass['nome_cidadao']  ?></div>

                                                <?= $data_ass . ' - ' . $ass['descricao'] . ' - ' . $ass['descricao_complemento'] ?>

                                                <code style="color: #4F4F4F;">
                                                    <h6>Coordenadoria - <?= $ass['nome_coordenadoria'] ?></h6>
                                                </code>

                                                <!-- <?= ($ass['status_atual'] == 'Inalterado') ? '' : 'style="color:green""' ?> -->
                                                <code <?= $cor ?>>
                                                    <h6><?= $status_assist ?></h6>
                                                </code>

                                            </h6>

                                        </button>

                                    </h2>
                                    <div id="ass_colapse<?= $ass['id'] ?>" class="accordion-collapse collapse" aria-labelledby="ass_flush<?= $ass['id'] ?>" data-bs-parent="#accordionFlushExample" style="background-color:#F0F8FF">
                                        <div class="accordion-body border">

                                            <!-- Botões -->
                                            <div class="mb-3">

                                                <?php if ($ass['status_assist'] != 'Finalizada') { ?>
                                                    <a href="<?= URL ?>/cidadao/cidadao/<?= $ass['id_cidadao'] ?>" class="btn btn-outline-primary btn-sm">Info Cidadão</a>
                                                    <a href="<?= URL ?>/assistencias/finalizar/<?= $ass['id'] ?>" class="btn btn-outline-success btn-sm">Finalizar</a>
                                                    <a href="<?= URL ?>/assistencias/update_status/<?= $ass['id'] ?>/<?= $ass['status_assist'] ?>" class="btn btn-outline-secondary btn-sm">Atualizar</a>
                                                <?php } ?>

                                            </div>

                                            <hr>

                                            <!-- Updates -->
                                            <?php foreach ($ass['update'] as $up) {

                                                $dt_up = new DateTime($up['updated_at']);
                                                $data = $dt_up->format('d/m/Y');

                                                $up_alterada = 'Alterada em';

                                                if ($up['status_updated'] == 'Finalizada') {
                                                    $up_alterada = 'Finalizada em';
                                                }
                                                if ($up['status_updated'] == 'Iniciada') {
                                                    $up_alterada = 'Registrada em';
                                                }

                                            ?>

                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <div class="car-title"><?= $up_alterada ?> </div>
                                                        <p><?= $data ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="car-title">Descrição </div>
                                                        <p><?= $up['status_compl_updated'] ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="car-title">Operador </div>
                                                        <p><?= $up['name_updated_by'] ?></p>
                                                    </div>

                                                </div>

                                                <hr>

                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php }
                } else { ?>
                    <div class="card">
                        <div class="card-body">
                            <?= $dados['msg_assistencias'] ?>
                        </div>
                    </div>

                <?php }
                ?>

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

                        <!-- 
                    <input type="date" name="inicio" id="input_mes_ano" class="form-control">
                    <input type="date" name="mes" id="input_mes" class="form-control"> -->

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
                        <input type="submit" id="btn_por_data" class="btn btn-primary" value="Buscar">
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
        }
    </script>



</div>