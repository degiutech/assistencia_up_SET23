<?= Sessao::mensagem('coord') ?>

<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <div class="row mb-1">
        <div class="col-md-6">
            <div class="row">
                <div class="col-auto">
                    <h3>Coordenadorias</h3>
                </div>
                <div class="col-auto">
                    <a href="<?= URL ?>/admin/create_coordenadoria" class="btn btn-outline-secondary btn-sm">Nova</a>
                </div>
            </div>

        </div>

        <div class="col-md-6 d-flex justify-content-end align-items-end">

            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
            <?php } ?>
            <a href="<?= URL ?>/admin/all_coordenadores" class="btn btn-secondary">Coordenadores</a>
        </div>
    </div>


    <div class="card">

        <div class="card-body">

            <?php if (!empty($dados['coordenadorias'])) { ?>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Coordenadoria</th>
                            <th scope="col">Coordenadores</th>

                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados['coordenadorias'] as $coordenadoria) { ?>
                            <tr>
                                <td><a href="<?= URL ?>/admin/coordenadoria/<?= $coordenadoria['id_coordenadoria'] ?>/recentes" style="text-decoration: none;"><?= $coordenadoria['nome_coordenadoria'] ?></a></td>

                                <td>
                                    <?php if ($coordenadoria['coordenadores']) {
                                        foreach ($coordenadoria['coordenadores'] as $coordenadores) { ?>
                                            <div>
                                                <a href="<?= URL ?>/users/info_operador/<?= $coordenadores['id_coordenador'] ?>" style="text-decoration: none;">
                                                    <?= $coordenadores['nome_coordenador'] ?>
                                                </a>
                                            </div>
                                    <?php }
                                    } else {
                                        echo 'Não há Coordenadores';
                                    } ?>
                                </td>
                                <td>

                                <td>

                                    <a href="<?= URL ?>/admin/edit_coordenadoria/<?= $coordenadoria['id_coordenadoria'] ?>" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>


                                </td>
                                <td>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            <?php } else {
                echo 'Não há registros de Coordenadorias!';
            } ?>

        </div>

    </div>

</div>