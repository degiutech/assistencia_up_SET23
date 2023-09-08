<div class="container">

    <?= Sessao::mensagem('user'); ?>


    <div class="col-md-12">

        <div class="row">

            <div class="col">
                <h4 class="titulo_page">Cidadãos - cadastros recentes</h4>
                <h6>Data: <?= $dados['hoje'] ?></h6>
                <h6>Nº de Registros: <?= $dados['num_registros'] ?></h6>
            </div>

            <div class="col">
                <div class="d-flex justify-content-end">
                    <!-- <a href="<?= URL ?>/estoque/cadastro" class="btn btn-primary">Novo</a> -->
                </div>
            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <table id="cidadaos" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <script>
                var list = <?= $dados['cidadaos'] ?>;
                // var URL = $(".URL").val();
                var URL = '<?= URL ?>'

                $('#cidadaos').DataTable({
                    // order: [
                    //     [5, 'desc']
                    // ],
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
                            data: 'email'
                        },
                        {
                            data: 'cidade'
                        },
                        {
                            data: 'id'
                        }
                    ],
                    columnDefs: [{
                            // "targets": [5],
                            // "visible": false,
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
                                    ret = row.celular + ' <a href="https://wa.me/55'+cel+'?text='+msg+'" style="color:#128c7e" target="_blank"><i class="bi bi-whatsapp"></i></a>';
                                }
                                return ret;
                            }
                        },
                        {
                            'targets': 3,
                            'render': function(data, type, row, meta) {
                                var ret = "";
                                if (row.cidade != "") {
                                    ret = row.cidade + "/" + row.uf;
                                }
                                return ret;
                            }
                        },
                        {
                            'targets': 4,
                            'render': function(data, type, row, meta) {
                                var retorno = '<div class="row">' +

                                    '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/cidadao/cidadao/' + row.id + '"' +
                                    'title="Visualisar Cidadão"><i class="bi bi-eye"></i></a></div>' +

                                    '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/cidadao/edit/' + row.id + '"' +
                                    'title="Editar Cadastro"><i class="bi bi-pencil"></i></a></div>' +

                                    '<div class="col-auto"><a class="btn btn-link" href="' + URL + '/assistencias/create/' + row.id + '"' +
                                    'title="Nova Assistência"><i class="bi bi-emoji-wink"></i></a></div></div>';
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
                        title: 'Cidadãos',
                        messageTop: '<div>Data: <?= $dados['hoje'] ?></div>' +
                            '<div>Nº Registros: <?= $dados['num_registros'] ?></div>',

                    }],
                });
            </script>



        </div>

    </div>

</div>

<!-- '<div class="row">' +
                                
                                '</div>' -->