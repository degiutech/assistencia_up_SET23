<div class="container">

    <?= Sessao::mensagem('user'); ?>


    <div class="col-md-12">

        <div class="row">

            <div class="col -md-6">
                <h4 class="titulo_page">Operadores do Sistema</h4>
                <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">
            
            <?= SessaoDg::mensagem('assistencia') ?>

            <?php if ($dados['num_registros'] != 0) { ?>

                <table id="operadores" class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Celular</th>
                            <th>Local de Trabalho</th>
                            <th>Cidade</th>
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

                    $('#operadores').DataTable({
                        order: [
                            [5, 'desc']
                        ],
                        data: list,
                        responsive: true,
                        language: {
                            "sEmptyTable": "Não foi encontrado nenhum registro",
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
                                data: 'local_trabalho'
                            },
                            {
                                data: 'cidade_trabalho'
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
                                "targets": [7],
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
                                'targets': 6,
                                'render': function(data, type, row, meta) {
                                    var retorno = '<div class="row">' +

                                        '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/dgassistencia/update_operador/' + row.id + '"' +
                                        'title="Visualisar Operador"><i class="bi bi-pencil"></i></a></div>';
                                    return retorno;
                                }
                            },
                            {
                                targets: 5,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    if (cellData == 'Bloqueado') {
                                        $(td).css('color', 'red');
                                    }
                                    if (cellData == 'Desbloqueado') {
                                        $(td).css('color', 'green');
                                    }
                                }
                            },
                            {
                                targets: 4,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    if (cellData == 'Representante') {
                                        $(td).text('Assessoria');
                                    }
                                }
                            },
                            {
                                targets: 3,
                                'render': function(data, type, row, meta) {
                                    var retorno = row.cidade_trabalho + ' - ' + row.uf_trabalho;
                                    return retorno;
                                }
                            }
                        ],
                        lengthChange: false,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Operadores do Sistema',
                            messageTop: '<div>Data: <?= date('d/m/Y') ?></div>' +
                                '<div>Nº Registros: <?= $dados['num_registros'] ?></div>',

                        }],
                    });
                </script>

            <?php } else {
                echo 'Não há registros de Operadores!';
            } ?>

        </div>

    </div>

</div>