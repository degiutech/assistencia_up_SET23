<div class="container">

    <?= Sessao::mensagem('user'); ?>


    <h3 class="mt-5">Painel de Supervisão</h3>

    <div class="row mt-3">

        <div class="col-md-3 mt-3">

            <!-- ASSISTÊNCIAS -->
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Assistências</h5>

                    <h6 class="card-subtitle mb-2 text-muted">Todas <?= $dados['count_assistencias'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Não finalizadas <?= $dados['count_assistencias_nao_finalizadas'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Finalizadas <?= $dados['count_assistencias_finalizadas'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Registradas em <?= $dados['mes_ano_atual'] ?> <?= $dados['count_assistencias_mes_atual'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Atualizadas em <?= $dados['mes_ano_atual'] ?> <?= $dados['count_assistencias_updates_mes_atual'] ?></h6>

                </div>
                <div class="card-body">
                    <a href="javascript:divs_links_gerenciar_assistencias()" class="card-link">Gerenciar</a>
                    <div class="div_links_gerenciar_assistencias" style="display: none;">
                        <div class="card-body" id="">
                            <div><a href="<?= URL ?>/assistencias/filtro_geral" class="card-link">Geral</a></div>
                            <div><a href="<?= URL ?>/assistencias/filtro_coordenadoria" class="card-link">Por Coordenadoria</a></div>
                            <div><a href="<?= URL ?>/assistencias/filtro_operador" class="card-link">Por Operador</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CIDADÃOS -->
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Cidadãos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Todos <?= $dados['count_cidadaos'] ?></h6>
                    <!-- <h6 class="card-subtitle mb-2 text-muted">Com Assistências: <?= $dados['count_cidadaos_com_assistencia'] ?></h6> -->
                    <h6 class="card-subtitle mb-2 text-muted">Cadastrados no mês <?= $dados['count_cidadaos_cadastrados_no_mes'] ?></h6>
                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/cidadao/cadastros_recentes" class="card-link">Recentes</a>
                    <a href="<?= URL ?>/cidadao/create" class="card-link">Novo</a>
                </div>
            </div>
        </div>

        <!-- COORDENADORIAS -->
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Coordenadorias</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Todas <?= $dados['count_coordenadorias'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Coordenadores <?= $dados['count_coordenadores'] ?></h6>
                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/supervisao/all_coordenadorias" class="card-link">Todas</a>
                    <a href="<?= URL ?>/coordenacao/create" class="card-link">Nova</a>
                    <a href="<?= URL ?>/supervisao/all_coordenadores" class="card-link">Coordenadores</a>
                </div>
            </div>
        </div>

        <!-- OPERADORES -->
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Operadores</h5>
                    <!-- <h6 class="card-subtitle mb-2 text-muted">Todos <?= $dados['count_all_operadores'] ?></h6> -->
                    <h6 class="card-subtitle mb-2 text-muted">Assessores <?= $dados['count_all_representantes'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Coordenadores <?= $dados['count_coordenadores'] ?></h6>
                    <!-- <h6 class="card-subtitle mb-2 text-muted">Supervisores <?= $dados['count_all_supervisores'] ?></h6> -->
                    <!-- <h6 class="card-subtitle mb-2 text-muted">Administradores <?= $dados['count_all_admin'] ?></h6> -->

                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/operadores" class="card-link">Todos</a>
                    <a href="<?= URL ?>/users/create" class="card-link">Novo</a>
                </div>
            </div>
        </div>
    </div>

</div>