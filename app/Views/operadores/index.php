<div class="container">

    <?= Sessao::mensagem('user'); ?>


    <div class="col-md-12">

        <div class="row">

            <div class="col-md-6">
                <h4 class="titulo_page">Todos os Operadores</h4>
                <h6>Data: <?= $dados['hoje'] ?></h6>
                <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
            </div>

            <div class="d-flex justify-content-end align-items-end col-md-6 mb-1">
                <?php if (isset($dados['home'])) { ?>
                    <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
                <?php } ?>
            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <table id="operadores_admin" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Celular</th>
                        <th>Acesso</th>
                        <th>Status</th>
                        <th>Ações</th>
                        <th>Created_at</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <script>
                var list = <?= $dados['operadores'] ?>;
                // var URL = $(".URL").val();
                var URL = '<?= URL ?>'

                $('#operadores_admin').DataTable({
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
                            "sLast": "ltimo"
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
                            data: 'acesso'
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
                                if (row.celular != "" && row.celular != null) {
                                    let text = row.celular;
                                    text = text.replace("(", "");
                                    text = text.replace(")", "");
                                    text = text.replace(" ", "");
                                    let cel = text.replace("-", "");
                                    let msg = "Olá, " + row.nome + "!";
                                    ret = row.celular + ' <a href="https://wa.me/55' + cel + '?text=' + msg + '" style="color:#128c7e" target="_blank"><i class="bi bi-whatsapp"></i></a>';
                                    return ret;
                                } else {
                                    return "";
                                }
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
                        {
                                targets: 2,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    if (cellData == 'Representante') {
                                        $(td).text('Assessoria');
                                    }
                                }
                            },
                    ],
                    lengthChange: false,
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Operadores',
                        messageTop: '<div>Data: <?= $dados['hoje'] ?></div>' +
                            '<div>N Registros: <?= $dados['num_registros'] ?></div>',

                    }],
                });
            </script>



        </div>

    </div>

</div>