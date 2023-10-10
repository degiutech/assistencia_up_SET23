<style>
    #btn_aniversariantes_dia {
        animation: animate 1.5s linear infinite;
    }

    @keyframes animate {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }
</style>


<div class="container">

    <?= Sessao::mensagem('user'); ?>

    <div class="row mt-5">
        <div class="col-md-6">
            <h3>Painel do Administrador</h3>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-end">
            <!-- <div id="div_btn_aniversariantes"> -->

            <!-- </div> -->
        </div>
    </div>

    <div class="row mt-3">

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Assistências</h5>

                    <!-- Updates -->
                    <h6 class="card-subtitle mb-2 text-muted">Todas: <?= $dados['count_updates'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Não finalizadas: <?= $dados['count_updates_nao_finalizadas'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Finalizadas: <?= $dados['count_updates_finalizadas'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Novos Registros em <?= $dados['mes_ano_atual'] ?>: <?= $dados['count_updates_mes_atual'] ?></h6>

                    <h6 class="card-subtitle mb-2 text-muted">Atualizações em <?= $dados['mes_ano_atual'] ?>: <?= $dados['count_assistencias_updates_mes_atual'] ?></h6>

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

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Cidadãos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Todos: <?= $dados['count_cidadaos'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Total de assistidos: <?= $dados['count_todos_assistidos'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Com Assistências ativas: <?= $dados['count_com_assistencias_ativas'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Cadastrados em <?= $dados['mes_ano_atual'] ?>: <?= $dados['count_cidadaos_cadastrados_no_mes'] ?></h6>
                    <!-- <a href="<?= URL ?>/admin/aniversariantes_dia" id="btn_aniversariantes_dia" class="btn btn-link" style="margin-right: 5px;">Aniversariantes do dia</a> -->
                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/cidadao/cadastros_recentes" class="card-link">Recentes</a>
                    <!-- <a href="#" class="card-link">Filtros</a> -->
                    <a href="<?= URL ?>/cidadao/create" class="card-link">Novo</a>
                    <a href="<?= URL ?>/admin/aniversariantes_dia" id="btn_aniversariantes_dia" style="display: none;" class="card-link">Aniversariantes</a>
                    <input type="hidden" name="input_num_registros" id="input_num_registros" value="<?= $dados['num_cidadaos_nivers'] ?>">
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Coordenadorias</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Todas <?= $dados['count_coordenadorias'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Coordenadores <?= $dados['count_coordenadores'] ?></h6>
                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/admin/all_coordenadorias" class="card-link">Todas</a>
                    <a href="<?= URL ?>/admin/create_coordenadoria" class="card-link">Nova</a>
                    <a href="<?= URL ?>/admin/all_coordenadores" class="card-link">Coordenadores</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" style="height: 150px;">
                    <h5 class="card-title">Operadores</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Todos <?= $dados['count_all_operadores'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Assessores <?= $dados['count_all_representantes'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Coordenadores <?= $dados['count_coordenadores'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Supervisores <?= $dados['count_all_supervisores'] ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted">Administradores <?= $dados['count_all_admin'] ?></h6>

                </div>
                <div class="card-body">
                    <a href="<?= URL ?>/operadores" class="card-link">Todos</a>
                    <a href="<?= URL ?>/users/create" class="card-link">Novo</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(() => {

        if ($("#input_num_registros").val() != 0) {
            $("#btn_aniversariantes_dia").show();
        }

        setInterval(function() {
            var hora = new Date().toLocaleTimeString();
            document.getElementById('rel').innerHTML = hora;
        }, 1000);

    })
</script>