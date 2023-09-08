<div class="container">

    <?= Sessao::mensagem('user'); ?>


    <div class="col-md-12">

        <h3 class="titulo_page">Coordenadores <a href="<?= URL ?>/users/create" class="btn btn-outline-secondary btn-sm">Novo</a></h3>

        <div class="row">
            <div class="col-md-6">
                <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
            </div>

            <div class="d-flex justify-content-end col-md-6 mb-1">
                <?php if (isset($dados['home'])) { ?>
                    <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
                <?php } ?>
                <?php if (isset($dados['link_coordenadorias'])) { ?>
                    <a href="<?= $dados['link_coordenadorias'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">Coordenadorias</a>
                <?php } ?>
            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <?php if ($dados['num_registros'] != 0) { ?>

                <table id="coordenadores" class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Celular</th>
                            <th>Coordenadoria</th>
                            <th>Status</th>
                            <th>Ações</th>
                            <th>Created_at</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <script>
                    var list = <?= $dados['coordenadores'] ?>;
                    // var URL = $(".URL").val();
                    var URL = '<?= URL ?>'

                    $('#coordenadores').DataTable({
                        order: [
                            [5, 'desc']
                        ],
                        data: list,
                        responsive: true,
                        language: {
                            "sEmptyTable": "Não foi encontrado nenhum registo",
                            "sLoadingRecords": "A carregar...",
                            "sProcessing": "A processar...",
                            "sLengthMenu": "Mostrar _MENU_ registos",
                            "sZeroRecords": "Não foram encontrados resultados",
                            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registos",
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
                                data: 'nome'
                            },
                            {
                                data: 'celular'
                            },
                            {
                                data: 'nome_coordenadoria'
                            },
                            {
                                data: 'bloqueio'
                            },
                            {
                                data: 'id'
                            },
                            {
                                data: 'created_at'
                            }
                        ],
                        columnDefs: [{
                                "targets": [5],
                                "visible": false,
                                // "searchable": false
                            },
                            {
                                'targets': 1,
                                'render': function(data, type, row, meta) {
                                    let text = row.celular;
                                    text = text.replace("(", "");
                                    text = text.replace(")", "");
                                    text = text.replace(" ", "");
                                    let cel = text.replace("-", "");
                                    cel = "65999528319";
                                    let msg = "Olá, " + row.nome + "!";

                                    var ret = "";
                                    if (row.celular != "") {
                                        ret = row.celular + ' <a href="https://wa.me/55' + cel + '?text=' + msg + '" style="color:#128c7e" target="_blank"><i class="bi bi-whatsapp"></i></a>';
                                    }
                                    return ret;
                                }
                            },
                            {
                                'targets': 4,
                                'render': function(data, type, row, meta) {
                                    var retorno = '<div class="row">' +

                                        '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/users/info_operador/' + row.id + '"' +
                                        'title="Visualisar Operador"><i class="bi bi-eye"></i></a></div>' +

                                        '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/operadores/edit_operador/' + row.id + '"' +
                                        'title="Visualisar Operador"><i class="bi bi-pencil"></i></a></div>' +

                                        '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/users/config_operador/' + row.id + '"' +
                                        'title="Configurar Operador"><i class="bi bi-gear"></i></a></div></div>';
                                    return retorno;
                                }
                            },
                            // {
                            //     targets: 4,
                            //     createdCell: function(td, cellData, rowData, row, col) {
                            //         if (cellData == 'Período Grátis') {
                            //             $(td).css('color', 'blue');
                            //         } 
                            //         if (cellData == 'Adimplente') {
                            //             $(td).css('color', 'green');
                            //         }
                            //         if (cellData == 'Inadimplente') {
                            //             $(td).css('color', 'red');
                            //         }
                            //     }
                            // }
                        ],
                        lengthChange: false,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Coordenadores',
                            messageTop: '<div>Data: <?= $dados['hoje'] ?></div>' +
                                '<div>Nº Registros: <?= $dados['num_registros'] ?></div>',

                        }],
                    });
                </script>

            <?php } else {

                echo 'Não há registros de Coordenadores!';
            } ?>


        </div>

    </div>

</div>