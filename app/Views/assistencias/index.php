<div class="container">

    <?php Sessao::mensagem('assistencia') ?>

    <div class="row">

        <div class="col">
            <h4 class="titulo_page">Assistências Recentes</h4>
            <h6>Data: <?= $dados['hoje'] ?></h6>
            <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
        </div>

        <div class="col d-flex justify-content-end align-items-end mb-1">
            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
            <?php } ?>
            <!-- <a href="javascript:window.history.back()" class="btn btn-secondary" style="margin-right: 5px;">Voltar</a> -->
        </div>

    </div>

    <div class="card">
        <div class="card-body">

            Assistências recentes

            <!-- Tabela para md -->
            <div class="d-none d-sm-block">
                <table class="table table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Registro</th>
                            <th>Descrição</th>
                            <th>Coordenadoria</th>
                            <th>Cidadão</th>
                            <th>Status Atual</th>
                            <th>Atualização</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (isset($dados['assistencias']) && is_array($dados['assistencias'])) {
                            // echo json_encode($dados['assistencias']);
                            foreach ($dados['assistencias'] as $assist) { ?>
                                <tr>
                                    <td><?= $assist['data'] ?></td>
                                    <td><?= $assist['descricao'] ?></td>
                                    <td><?= $assist['nome_coordenadoria'] ?></td>
                                    <td><?= $assist['nome_cidadao'] ?></td>
                                    <td><?= $assist['status_assistencia'] ?></td>
                                    <td><?= $assist['ultima_alteracao'] ?></td>
                                    <td>
                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $assist['id_assistencia'] ?>" title="Detalhes da Assistência"><i class="bi bi-eye"></i></a>
                                        <a href="<?= URL ?>/assistencias/update_status/<?= $assist['id_assistencia'] ?>/<?= $assist['status_assistencia'] ?>" class="p-3" title="Alterar Status"><i class="bi bi-pencil-square"></i></a>
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
                            <th>Nome do Assistido</th>
                            <th>Status Atual</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dados['assistencias']) && is_array($dados['assistencias'])) {
                            // echo json_encode($dados['assistencias']);
                            foreach ($dados['assistencias'] as $assist) { ?>

                                <tr>
                                    <td><?= $assist['nome_cidadao'] ?></td>
                                    <td><?= $assist['status_assistencia'] ?></td>
                                    <td>
                                        <a href="<?= URL ?>/assistencias/assistencia/<?= $assist['id_assistencia'] ?>" title="Detalhes da Assistência"><i class="bi bi-eye"></i></a>
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