<?php

Sessao::mensagem('user');

if (isset($dados['op'])) {
    $op = $dados['op'];

    $d = date_create($op['created_at']);
    $data_cadastro = date_format($d, 'd/m/Y');

?>


    <div class="container">


        <div class="d-flex justify-content-center">

            <div class="col-md-10 mt-3">

                <div class="row mb-1">
                    <div class="col-md-6">
                        <h3>
                            <?php if ($_SESSION['user']['acesso'] == 'Coordenadoria') {
                                echo 'Informações de Assessor(a)';
                            } else {
                                echo 'Informações de Operador(a)';
                            } ?>
                        </h3>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        <?php if (isset($dados['home'])) { ?>
                            <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
                        <?php } ?>
                        <a href="<?= URL ?>/admin/all_operadores" class="btn btn-secondary">Operadores</a>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">

                        <table class="table">
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        <h4>Nome:</h4>
                                    </th>
                                    <td>
                                        <h4 class="cor-texto"><?= mb_strtoupper($op['nome'], 'UTF-8') ?></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Acesso:</th>
                                    <td><?php if ($op['acesso'] == 'Representante') {
                                            echo 'Assessoria';
                                        } else {
                                            echo $op['acesso'];
                                        } ?></td>
                                </tr>
                                <?php if (isset($op['nome_coordenadoria'])) { ?>
                                    <tr>
                                        <th>Coordenadoria:</th>
                                        <td><?= $op['nome_coordenadoria'] ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>Restrição:</th>
                                    <td><?= $op['bloqueio'] ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= $op['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Celular:</th>
                                    <td><?= $op['celular'] ?></td>
                                </tr>

                                <?php if (!empty($op['cep'])) { ?>
                                    <tr>
                                        <th>Enderço residencial:</th>
                                        <td><?= $op['logradouro'] . ',' . $op['numero'] . ', ' . $op['complemento'] . ', ' . $op['bairro'] . ', '
                                                . $op['cidade'] . ' - ' . $op['uf'] ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>Local de trabalho:</th>
                                    <td><?= $op['local_trabalho'] ?></td>
                                </tr>
                                <tr>
                                    <th>Cadastrado no Sistema em:</th>
                                    <td><?= $data_cadastro ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">

                            <div class="col-md-6 mt-3 d-flex justify-content-start">

                                <a href="javascript:window.history.back()" class="btn btn-outline-success">Voltar</a>
                                <a href="<?= URL ?>/operadores/edit_operador/<?= $op['id'] ?>" class="btn btn-info" style="margin-left: 10px;">Editar</a>
                                <a href="<?= URL ?>/users/config_operador/<?= $op['id'] ?>" class="btn btn-primary" style="margin-left: 10px;">Configurar</a>

                            </div>

                            <div class="col-md-6 d-flex justify-content-end">

                                <!-- <a href="http://"><i class="bi bi-facebook" style="font-size: 2rem;"></i></a>
                                <a href="http://" style="margin-left: 10px;"><i class="bi bi-whatsapp" style="font-size: 2rem;"></i></a>
                                <a href="http://" style="margin-left: 10px;"><i class="bi bi-meta" style="font-size: 2rem;"></i></a>
                                <a href="http://" style="margin-left: 10px;"><i class="bi bi-messenger" style="font-size: 2rem;"></i></a> -->
                            </div>

                        </div>


                    </div>
                </div>

            </div>

        </div>

    </div>

<?php } ?>



<div class="container">

    <div class="d-flex justify-content-center">

        <div class="col-md-10">

            <!-- Ações -->

            <h3 class="mt-3 mb-1">Ações</h3>
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        Todas: <b><?= $dados['count_todas'] ?></b> |
                        1º registro: <b><?= $dados['count_primeiro_registro'] ?></b> |
                        Atualizações: <b><?= $dados['count_atualizacoes'] ?></b> |
                        Finalizações: <b><?= $dados['count_finalizacoes'] ?></b>
                    </div>

                    <?php if ($dados['acoes'] != '') { ?>

                        <table id="acoes" class="table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Visualizar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <script>
                            var list = <?= $dados['acoes'] ?>;
                            // var URL = $(".URL").val();
                            var URL = '<?= URL ?>'

                            $('#acoes').DataTable({
                                // order: [
                                //     [5, 'desc']
                                // ],
                                data: list,
                                responsive: true,
                                language: {
                                    "sEmptyTable": "Nenhum registro encontrado",
                                    "sLoadingRecords": "A carregar...",
                                    "sProcessing": "A processar...",
                                    "sLengthMenu": "Mostrar _MENU_ registros",
                                    "sZeroRecords": "Não foram encontrados resultados",
                                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                                    "sInfoEmpty": "Mostrando de 0 até 0 de 0 registos",
                                    "sInfoFiltered": "(filtrado de _MAX_ registos no total)",
                                    "sInfoPostFix": "",
                                    "sSearch": "Procurar:",
                                    "sUrl": "",
                                    "oPaginate": {
                                        "sFirst": "Primeiro",
                                        "sPrevious": "Anterior",
                                        "sNext": "Seguinte",
                                        "sLast": "Último"
                                    },
                                    "oAria": {
                                        "sSortDescending": ": Ordenar colunas de forma descendente",
                                        "sSortAscending": ": Ordenar colunas de forma ascendente"

                                    }
                                },
                                columns: [{
                                        data: 'data'
                                    },
                                    {
                                        data: 'tipo'
                                    },
                                    {
                                        data: 'descricao'
                                    },
                                    {
                                        data: 'id_assistencia'
                                    }
                                ],
                                columnDefs: [{
                                        // "targets": [3],
                                        // "visible": false,
                                        // "searchable": false
                                    },
                                    // {
                                    //     'targets': 1,
                                    //     'render': function(data, type, row, meta) {
                                    //         let text = row.celular;
                                    //         text = text.replace("(", "");
                                    //         text = text.replace(")", "");
                                    //         text = text.replace(" ", "");
                                    //         let cel = text.replace("-", "");
                                    //         cel = "65999528319";
                                    //         let msg = "Olá, " + row.nome + "!";

                                    //         var ret = "";
                                    //         if (row.celular != "") {
                                    //             ret = row.celular + ' <a href="https://wa.me/55' + cel + '?text=' + msg + '" style="color:#128c7e" target="_blank"><i class="bi bi-whatsapp"></i></a>';
                                    //         }
                                    //         return ret;
                                    //     }
                                    // },
                                    {
                                        'targets': 3,
                                        'render': function(data, type, row, meta) {
                                            var retorno = '<div class="row">' +

                                                '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/assistencias/assistencia/' + row.id_assistencia + '"' +
                                                'title="Visualisar Operador"><i class="bi bi-eye"></i></a></div>';
                                            return retorno;
                                        }
                                    },
                                    // {
                                    //     targets: 4,
                                    //     createdCell: function(td, cellData, rowData, row, col) {
                                    //         if (cellData == 'Bloqueado') {
                                    //             $(td).css('color', 'red');
                                    //         }
                                    //         if (cellData == 'Desbloqueado') {
                                    //             $(td).css('color', 'green');
                                    //         }
                                    //     }
                                    // }
                                ],
                                // lengthChange: false,
                                // dom: 'Bfrtip',
                                // buttons: [{
                                //     extend: 'print',
                                //     text: 'Imprimir',
                                //     title: 'Representantes',
                                //     messageTop: '<div>Data: <?= $dados['hoje'] ?></div>' +
                                //         '<div>Nº Registros: <?= $dados['num_registros'] ?></div>',

                                // }],
                            });
                        </script>

                    <?php } else {
                        echo 'Não há registros!';
                    } ?>

                </div>
            </div>
        </div>
    </div>
</div>