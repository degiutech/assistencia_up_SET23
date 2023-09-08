<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <?= Sessao::mensagem('cidadao') ?>

    <div class="row mb-2">
        <div class="col-md-6">
            <h3>Cidadãos</h3>
            <div>Cadastros recentes</div>
        </div>

        <div class="d-flex justify-content-end align-items-end col-md-6">
            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 10px;">HOME</a>
            <?php } ?>
            <a href="<?= URL ?>/cidadao/create" class="btn btn-secondary">Novo Cidadão</a>
        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <?php if (!$dados['cidadaos']) {
                echo 'Não há registros de Cidadãos!';
            } else { ?>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Celular</th>
                            <th scope="col">Cidade</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados['cidadaos'] as $cidadao) { ?>
                            <tr>
                                <td><?= $cidadao['nome'] ?></th>

                                <td><?= $cidadao['celular'] ?>

                                    <?php if (isset($cidadao['celular']) && !empty($cidadao['celular'])) {

                                        // Whatsapp
                                        $num = $cidadao['celular'];
                                        $sem_parenteses1 = str_replace('(', '', $num);
                                        $sem_parenteses2 = str_replace(')', '', $sem_parenteses1);
                                        $sem_espaco = str_replace(' ', '', $sem_parenteses2);
                                        $whatsapp = str_replace('-', '', $sem_espaco);

                                    ?>

                                        <a href="https://wa.me/55<?= $whatsapp ?>?text=Olá, <?= $cidadao['nome'] ?>!" target="_blank"><i class="bi bi-whatsapp" style="margin-left: 5px; color:#128c7e;"></i></a>

                                    <?php } ?>

                                </td>

                                <td><?= $cidadao['cidade'] ?></td>

                                <td>
                                    <a href="<?= URL ?>/cidadao/cidadao/<?= $cidadao['id'] ?>" title="Página do Cidadão" style="text-decoration: none;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= URL ?>/cidadao/edit/<?= $cidadao['id'] ?>" title="Editar Cidadão" style="margin-left:10px; text-decoration: none;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= URL ?>/diversos/create_assistencia/<?= $cidadao['id'] ?>" title="Registrar Assintência" style="margin-left:10px; text-decoration: none;">
                                        <i class="bi bi-emoji-wink"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            <?php } ?>

        </div>

    </div>

</div>