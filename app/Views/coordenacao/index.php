<div class="container">

    <?= Sessao::mensagem('user'); ?>
    <?= Sessao::mensagem('assistencias'); ?>

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
                    <!-- <h3 class="cor-texto"><b><?= $dados['nome_coordenadoria'] ?></b></h3> -->
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


    <div class="mt-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Assistências</h5>

                <h6 class="card-subtitle mb-2 text-muted">Todas <?= $dados['count_assistencias'] ?></h6>

                <h6 class="card-subtitle mb-2 text-muted">Não finalizadas <?= $dados['count_nao_finalizadas'] ?></h6>

                <h6 class="card-subtitle mb-2 text-muted">Finalizadas <?= $dados['count_finalizadas'] ?></h6>

                <h6 class="card-subtitle mb-2 text-muted">Novos registros no mês atual <?= $dados['count_novas_mes_atual'] ?></h6>

                <h6 class="card-subtitle mb-2 text-muted">Atualizadas no mês atual <?= $dados['count_up_mes_atual'] ?></h6>

                <a href="<?= URL ?>/coordenacao/assistencias" class="btn btn-secondary">Gerenciar Assistências</a>

            </div>
        </div>
    </div>

    <!-- Assistências recentes -->
    <div class="card mt-3">
        <div class="card-body">

            <div class="row mb-3">
                <h5>Assistências recentes desta Coordenadoria</h5>
            </div>


            <!-- Tabela para md -->
            <!-- <div class="d-none d-sm-block"> -->
            <div class="d-none d-sm-block">
                <table class="table table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Data 1º registro</th>
                            <th>Descrição</th>
                            <th>Data de Atualização</th>
                            <th>Desc. de Atualização</th>
                            <th>Status Atual</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (isset($dados['assistencias']) && is_array($dados['assistencias'])) {
                            // echo json_encode($dados['assistencias']);
                            foreach ($dados['assistencias'] as $assist) { ?>
                                <tr>
                                    <td><?= $assist['dt_primeiro_registro'] ?></td>
                                    <td><?= $assist['desc_primeiro_registro'] ?></td>
                                    <td><?= $assist['dt_ultima_atualizacao'] ?></td>
                                    <td><?= $assist['desc_ultima_atualização'] ?></td>
                                    <td><?= $assist['status_atual'] ?></td>
                                    <td>
                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $assist['id_primeiro_registro'] ?>" title="Detalhes da Assistência"><i class="bi bi-eye"></i></a>
                                        <a href="<?= URL ?>/assistencias/update_status/<?= $assist['id_primeiro_registro'] ?>/<?= $assist['status_atual'] ?>" class="p-3" title="Atualizar assistência"><i class="bi bi-pencil-square"></i></a>
                                    </td>
                                </tr>
                        <?php }
                        } ?>

                    </tbody>
                </table>

            </div>

            <!-- Tabela para sm -->
            <div class="d-block d-sm-none">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Dt. Atualização</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dados['assistencias']) && is_array($dados['assistencias'])) {
                            // echo json_encode($dados['assistencias']);
                            foreach ($dados['assistencias'] as $assist) { ?>

                                <tr>
                                    <td><?= $assist['dt_ultima_atualizacao'] ?></td></td>
                                    <td><?= $assist['desc_ultima_atualização'] ?></td>
                                    <td><?= $assist['status_atual'] ?></td>
                                    <td>
                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $assist['id_primeiro_registro'] ?>" title="Detalhes da Assistência"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</div>