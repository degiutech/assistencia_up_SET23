Meus Registros de Assistências

<div class="row mb-1">
    <div class="col-md-6">
        <h5 class="mt-4"></h5>
    </div>
    <div class="col-md-6 d-flex justify-content-md-end align-items-end">

        <?php if (isset($dados['home'])) { ?>
            <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block btn-sm" style="float:right; margin-right: 5px">HOME</a>
        <?php } ?>

        <a href="<?= URL ?>/cidadao/create" class="btn btn-secondary btn-sm" style="margin-right: 5px;">Novo Cidadão</a>

        <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary btn-sm" style="margin-right: 5px;">Cidadãos</a>

    </div>
</div>

<!-- <div class="row"> -->

<div class="mb-3">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">

                    <!-- botão recentes -->
                    <?php if ($dados['titulo_botao'] == 'recentes') { ?>
                        <a href="" class="btn btn-secondary btn-sm disabled">Recentes</a>
                    <?php } else { ?>
                        <a href="<?= URL ?>/representante" class="btn btn-outline-secondary btn-sm">Recentes</a>
                    <?php } ?>

                    <!-- botão não finalizadas -->
                    <?php if ($dados['titulo_botao'] == 'não finalizadas') { ?>
                        <a href="" class="btn btn-secondary btn-sm disabled">Não Finalizadas</a>
                    <?php } else { ?>
                        <a href="<?= URL ?>/representante/minhas_ass_nao_finalizadas" class="btn btn-outline-secondary btn-sm">Não Finalizadas</a>
                    <?php } ?>

                    <!-- botão finalizadas -->
                    <?php if ($dados['titulo_botao'] == 'finalizadas') { ?>
                        <a href="" class="btn btn-secondary btn-sm disabled">Finalizadas</a>
                    <?php } else { ?>
                        <a href="<?= URL ?>/representante/minhas_ass_finalizadas" class="btn btn-outline-secondary btn-sm">Finalizadas</a>
                    <?php } ?>

                    <!-- busca por data -->
                    <?php if ($dados['titulo_botao'] == 'por data') { ?>
                        <a href="javascript:por_data()" class="btn btn-secondary btn-sm" id="link_por_data">Por Data</a>
                    <?php } else { ?>
                        <a href="javascript:por_data()" class="btn btn-outline-secondary btn-sm" id="link_por_data">Por Data</a>
                    <?php } ?>

                    <!-- busca por mês e ano -->
                    <?php if ($dados['titulo_botao'] == 'por mes') { ?>
                        <a href="javascript:por_mes_ano()" class="btn btn-secondary btn-sm">Mês/ano</a>
                    <?php } else { ?>
                        <a href="javascript:por_mes_ano()" class="btn btn-outline-secondary btn-sm">Mês/ano</a>
                    <?php } ?>

                    <!-- busca por período -->
                    <?php if ($dados['titulo_botao'] == 'por período') { ?>
                        <a href="javascript:por_periodo()" class="btn btn-secondary btn-sm">Período</a>
                    <?php } else { ?>
                        <a href="javascript:por_periodo()" class="btn btn-outline-secondary btn-sm">Período</a>
                    <?php } ?>

                </div>
            </div>
        </div>
    </nav>









    <!-- <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary" style="float:right">Cidadãos</a> -->


</div>

<div class="card">
    <div class="card-body">
        <?= $dados['titulo_minhas'] ?> <b><?= $dados['nome_operador'] ?></b>
        <p>Registros: <?= $dados['num_registros'] ?></p>

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
            <form action="<?= URL ?>/representante/minhas_ass_por_data" method="post">
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
            <form action="<?= URL ?>/representante/minhas_ass_mes_ano" method="post">

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
            <form action="<?= URL ?>/representante/minhas_ass_periodo" method="post">

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
            // $("#link_por_data").removeClass("btn-outline-secondary").addClass("btn-secondary")
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