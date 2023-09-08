<div class="container">

    <?= Sessao::mensagem('aniversariantes'); ?>


    <div class="col-md-12">

        <div class="row">

            <div class="col -md-6">
                <h4 class="titulo_page">Cidadãos aniversariantes</h4>
                <h6>Data: <?= date('d/m/Y') ?></h6>
                <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
            </div>

            <div class="col-md-6 d-flex justify-content-end align-items-end mb-1">
                <?php if (isset($dados['home'])) { ?>
                    <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
                <?php } ?>
                <a href="javascript:window.history.back()" class="btn btn-secondary" style="margin-right: 5px;">Voltar</a>
            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <?php if ($dados['num_registros'] > 0) { ?>

                <table id="aniversariantes" class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data nascimento</th>
                            <th>Idade</th>
                            <th>Celular</th>
                            <th>Email</th>
                            <th>Mensagem</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <script>
                    var list = <?= $dados['aniversariantes'] ?>;
                    // var URL = $(".URL").val();
                    var URL = '<?= URL ?>'

                    $('#aniversariantes').DataTable({
                        // order: [
                        //     [5, 'desc']
                        // ],
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
                                data: 'data_nascimento'
                            },
                            {
                                data: 'idade'
                            },
                            {
                                data: 'celular'
                            },
                            {
                                data: 'email'
                            },
                            {
                                data: 'id_cidadao'
                            }
                        ],
                        columnDefs: [{
                                // "targets": [4],
                                // "visible": false,
                                // "searchable": false
                            },
                            {
                                'targets': 3,
                                'render': function(data, type, row, meta) {
                                    let text = row.celular;
                                    text = text.replace("(", "");
                                    text = text.replace(")", "");
                                    text = text.replace(" ", "");
                                    let cel = text.replace("-", "");
                                    let msg = "Olá, " + row.nome + "!";

                                    var ret = "";
                                    if (row.celular != "") {
                                        ret = row.celular + ' <a href="https://wa.me/55' + cel + '?text=' + msg + '" style="color:#128c7e" target="_blank"><i class="bi bi-whatsapp"></i></a>';
                                    }
                                    return ret;
                                }
                            },
                            {
                                'targets': 5,
                                'render': function(data, type, row, meta) {
                                    var retorno = 
                                        '<a class="btn btn-outline-secondary btn-sm" href="' + URL + '/admin/msg_aniversario/' + row.id_cidadao + '"' +
                                        'title="Enviar mensagens de aniversário">Enviar</a>';
                                    return retorno;
                                }
                            },
                            // {
                            //     targets: 5,
                            //     createdCell: function(td, cellData, rowData, row, col) {
                            //         if (cellData == 'Bloqueado') {
                            //             $(td).css('color', 'red');
                            //         }
                            //         if (cellData == 'Desbloqueado') {
                            //             $(td).css('color', 'green');
                            //         }
                            //     }
                            // },
                        ],
                        lengthChange: false,
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Cidadãos aniversariantes do mês',
                            messageTop: '<div>Data: <?= date('d/m/Y') ?></div>' +
                                '<div>Nº Registros: <?= $dados['num_registros'] ?></div>',

                        }],
                    });
                </script>

            <?php } else {
                echo 'Não há registros de aniversariantes do dia!';
            } ?>

        </div>

    </div>

</div>
