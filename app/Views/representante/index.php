<?= Sessao::mensagem('user'); ?>

<div class="container">

    <div class="row mb-1">
        <div class="col-md-6">
            <h3 class="mt-4">Assistências</h3>

            <div class="row">

                <div class="col-auto cor-texto">
                    Todas (<?= $dados['count_geral'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Não finalizadas (<?= $dados['count_geral_nao_finalizadas'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Finalizadas (<?= $dados['count_geral_finalizadas'] ?>)
                </div>

            </div>

        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-end">

            <?php if (isset($dados['home'])) { ?>
                <!-- <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
            <?php } ?>

            <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary" style="margin-right: 5px;">Cidadãos</a> -->

        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid p-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-2">

                    <li class="nav-item">
                        <!-- botão recentes -->
                        <?php if ($dados['titulo_botao'] == 'recentes') { ?>
                            <a href="<?= URL ?>/representante/index" class="btn btn-secondary d-block">Recentes</a>
                        <?php } else { ?>
                            <a href="<?= URL ?>/representante/index" class="btn btn-outline-secondary d-block">Recentes</a>
                        <?php } ?>
                    </li>

                    <li class="nav-item">
                        <!-- botão não finalizadas -->
                        <?php if ($dados['titulo_botao'] == 'não finalizadas') { ?>
                            <a href="<?= URL ?>/representante/minhas_assistencias_nao_finalizadas" class="btn btn-secondary d-block">Não Finalizadas</a>
                        <?php } else { ?>
                            <a href="<?= URL ?>/representante/minhas_assistencias_nao_finalizadas" class="btn btn-outline-secondary d-block">Não Finalizadas</a>
                        <?php } ?>
                    </li>

                    <li class="nav-item">
                        <!-- botão finalizadas -->
                        <?php if ($dados['titulo_botao'] == 'finalizadas') { ?>
                            <a href="<?= URL ?>/representante/minhas_assistencias_finalizadas" class="btn btn-secondary d-block">Finalizadas</a>
                        <?php } else { ?>
                            <a href="<?= URL ?>/representante/minhas_assistencias_finalizadas" class="btn btn-outline-secondary d-block">Finalizadas</a>
                        <?php } ?>
                    </li>

                    <li class="nav-item">
                        <?php if ($dados['titulo_botao'] == 'por_data') { ?>
                            <a href="javascript:por_data()" class="btn btn-secondary d-block" id="link_por_data">Por Data</a>
                        <?php } else { ?>
                            <a href="javascript:por_data()" class="btn btn-outline-secondary d-block" id="link_por_data">Por Data</a>
                        <?php } ?>
                    </li>

                    <li class="nav-item">
                        <!-- busca por mês e ano -->
                        <?php if ($dados['titulo_botao'] == 'mes_ano') { ?>
                            <a href="javascript:por_mes_ano()" class="btn btn-secondary d-block">Mês/ano</a>
                        <?php } else { ?>
                            <a href="javascript:por_mes_ano()" class="btn btn-outline-secondary d-block">Mês/ano</a>
                        <?php } ?>
                    </li>

                    <li class="nav-item">
                        <!-- busca por período -->
                        <?php if ($dados['titulo_botao'] == 'periodo') { ?>
                            <a href="javascript:por_periodo()" class="btn btn-secondary d-block">Período</a>
                        <?php } else { ?>
                            <a href="javascript:por_periodo()" class="btn btn-outline-secondary d-block">Período</a>
                        <?php } ?>
                    </li>

                </ul>
                <div class="d-flex d-none d-lg-block">
                    <!-- <div class="col-md-5 d-none d-lg-block"> -->

                    <a href="<?= URL ?>/cidadao/create" class="btn btn-secondary" style="float:right">Cadastro de Cidadão</a>

                    <a href="<?= URL ?>/cidadao/cadastros_recentes" class="btn btn-secondary" style="float:right;">Cidadãos</a>

                    <?php if (isset($dados['home'])) { ?>
                        <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right;">HOME</a>
                    <?php } ?>

                    <!-- </div> -->
                </div>
            </div>
        </div>
    </nav>

    <!-- Assistências -->
    <div class="card col-12">
        <div class="card-body">

            <?= Sessao::mensagem('assistencias') ?>

            <div><?= $dados['titulo'] ?></div>
            <div><?= $dados['num_registros'] ?></div>
            <div class="mb-3"><?= $dados['count_assistidos'] ?></div>




            <?php if ($dados['assistencias']) {
                foreach ($dados['assistencias'] as $ass) { ?>

                    <div class="card mb-3 meu_hover">
                        <div class="card-body">
                            <div>Assistido(a): <?= $ass['nome_cidadao'] ?></div>
                            <div>Descrição: <?= $ass['descricao'] ?></div>
                            <div>Data: <?= $ass['data'] ?></div>
                            <div>Tipo: <?= $ass['tipo'] ?></div>
                            <div>Cidadão assistido desde <?= $ass['data_primeiro_registro'] ?></div>
                            <div>Coordenadoria: <?= $ass['nome_coordenadoria'] ?></div>

                            <div class="mt-2">

                                <a href="<?= URL ?>/cidadao/cidadao/<?= $ass['id_cidadao'] ?>" class="btn btn-outline-primary btn-sm">Info Cidadão</a>

                                <?php if ($ass['tipo'] != 'Finalização') { ?>

                                    <a href="<?= URL ?>/assistencias/finalizar/<?= $ass['id_primeiro_registro'] ?>" class="btn btn-outline-dark btn-sm">Finalizar</a>
                                    <a href="<?= URL ?>/assistencias/update_status/<?= $ass['id_primeiro_registro'] ?>/<?= $ass['status_assist'] ?>" class="btn btn-outline-secondary btn-sm">Atualizar</a>
                                <?php } ?>

                                <a href="<?= URL ?>/assistencias/assistencia/<?= $ass['id_primeiro_registro'] ?>" class="btn btn-outline-success btn-sm">Histórico</a>
                            </div>

                        </div>

                    </div>

            <?php }
            } else {
                echo 'Não há registros de Assistências';
            } ?>

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

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_assistencia1" value="não finalizadas">
                            <label class="form-check-label" for="status_assistencia1">
                                Não finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_assistencia2" value="finalizadas">
                            <label class="form-check-label" for="status_assistencia2">
                                Finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_assistencia3" value="todas" checked>
                            <label class="form-check-label" for="status_assistencia3">
                                Todas
                            </label>
                        </div>

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

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_mes_ano1" value="não finalizadas">
                            <label class="form-check-label" for="status_mes_ano1">
                                Não finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_mes_ano2" value="finalizadas">
                            <label class="form-check-label" for="status_mes_ano2">
                                Finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_mes_ano3" value="todas" checked>
                            <label class="form-check-label" for="status_mes_ano3">
                                Todas
                            </label>
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

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_periodo1" value="não finalizadas">
                            <label class="form-check-label" for="status_periodo1">
                                Não finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_periodo2" value="finalizadas">
                            <label class="form-check-label" for="status_periodo2">
                                Finalizadas
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_assistencia" id="status_periodo3" value="todas" checked>
                            <label class="form-check-label" for="status_periodo3">
                                Todas
                            </label>
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
        $("#btn_periodo_provisorio").on("click", () => {
            let dt_inicial = $("#dt_inicial").val()
            let dt_final = $("#dt_final").val()
            if (dt_inicial == "" || dt_final == "") {
                alert("Informe corretamente a data inicial e a data final!")
            } else {
                $("#btn_periodo").click()
            }
        })
    </script>

</div>