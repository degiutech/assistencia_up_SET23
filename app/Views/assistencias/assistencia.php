<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <div class="d-flex justify-content-end">
        <?php if (isset($dados['home'])) { ?>
            <a href="<?= $dados['home'] ?>" style="margin-right: 10px;" class="btn btn-success">HOME </a>
        <?php } ?>
        <a href="<?= URL ?>/cidadao/cidadao/<?= $dados['cidadao']['id'] ?>" class="btn btn-secondary">Info Cidadão</a>
        <a href="<?= URL ?>/assistencias/create/<?= $dados['cidadao']['id'] ?>" class="btn btn-info" style="margin-left: 10px;">Nova Assistência</a>
    </div>

    <h3>Histórico de Assistência</h3>

    <div class="card">

        <div class="card-body">

            <?php if ($dados['status_atual'] == 'Finalizada') { ?>
                <div class="alert alert-primary"><b>ASSISTÊNCIA FINALIZADA</b></div>
            <?php } ?>

            <h5>Assistido(a): <span class="cor-texto"><b><?= $dados['cidadao']['nome'] ?></b></span></h5>


            <div>Descrição inicial: <b><?= $dados['assistencia']['descricao'] ?> - <?= $dados['assistencia']['descricao_complemento'] ?></b>
            </div>


            <div>Status atual: <b><?= $dados['status_atual'] ?> - <?= $dados['status_complemento'] ?></b></div>

            <!-- Data e Hora de Status atual
            <p><b><?= $dados['data_hora'] ?></b></p>

            Complementação de Status atual
            <p><b><?= $dados['status_complemento'] ?></b></p> -->


            <div>Primeiro registro: <b><?= $dados['data_hora_registro'] ?></b></div>
            <div>Última atualização: <b><?= $dados['data_hora'] ?></b></div>
            <div>Coordenadoria: <b><?= $dados['assistencia']['nome_coordenadoria'] ?></b></div>

            <?php if ($dados['assistencia']['id_coordenadoria'] == 2) { ?>
                <div>Processo Jurídico: <b><?= $dados['assistencia']['desc_juridica'] . ' - ' . $dados['assistencia']['num_proc_juridica'] ?></b></div>
            <?php } ?>

            <div class="mb-2">Primeiro registro feito por: <b><?= $dados['assistencia']['name_created_by'] ?></b></div>

            <?php if ($dados['status_atual'] != 'Finalizada') { ?>
                <a href="<?= URL ?>/admin/update_status_assistencia/<?= $dados['assistencia']['id'] ?>/<?= $dados['assistencia']['status_assist'] ?>" class="btn btn-primary">Atualizar</a>
                <a href="<?= URL ?>/admin/finalizar_assistencia/<?= $dados['assistencia']['id'] ?>" class="btn btn-primary">Finalizar</a>
            <?php } ?>


            <hr>

            <!-- Histórico -->
            <h5 class="mt-3">Atualizações (<?= $dados['count_atualizacoes'] ?>)</h5>

            <?php if (count($dados['updates']) == 1) { ?>
                <p>Esta Assistência não contém atualizaões!</p>
            <?php } else { ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Data e hora</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Status</th>
                            <th scope="col">Atualizada por</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($dados['updates'] as $up) { ?>

                            <tr>
                                <td><?= $up['data_hora'] ?></td>

                                <td><?= $up['complemento_updated'] ?></td>

                                <td><?php if ($up['status_updated'] == 'Iniciada') { ?>
                                        Primeiro registro
                                    <?php
                                    } else {
                                        echo $up['status_updated'];
                                    } ?>
                                </td>

                                <td><?= $up['name_updated_by'] ?></td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>

            <?php } ?>


        </div>

    </div>

</div>