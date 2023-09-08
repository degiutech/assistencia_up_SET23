<h1>Nav de Filtros</h1>

<div class="container">

    <div class="card">
        <div class="card-body">

            <div class="row">

                <!-- Busca -->
                <div class="col-md-3" role="search">

                    <input type="hidden" id="id_coordenadoria_operador" value="<?= $dados['id_coordenadoria'] ?>">

                    <label for="input_busca_operador">Asssistências por operador</label>

                    <input class="form-control" type="search" id="input_busca_operador" placeholder="Nome de Operador" aria-label="Search">
                    <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->

                    <div class="col-sm-12">

                        <div class="card" id="div_result_busca_operador" style="position: absolute; z-index: 10; max-width: 100%; display: none;">

                            <div class="card-body" id="result_busca_operador" style="width: 295px;">

                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">
                    <a href="" class="btn btn-info">Filtro por xxx</a>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function() {

        $("#input_busca_operador").keyup(() => {

            //Adequando a url do ajax
            let url = "../ajax/busca_operador_by_nome"
            let url_atual = window.location.href
            let separa_url = url_atual.split("/");
            if (separa_url.length === 5) {
                url = "./ajax/busca_operador_by_nome"
            }
            if (separa_url.length === 7) {
                url = "../../ajax/busca_operador_by_nome"
            }
            if (separa_url.length === 8) {
                url = "../../../ajax/busca_operador_by_nome"
            }

            let texto = $("#input_busca_operador").val()
            let id_coordenadoria = $("#id_coordenadoria_operador").val()

            if (texto.length >= 3) {
                $("#div_result_busca_operador").show()

                $.ajax({
                        method: "POST",
                        url: url,

                        data: {
                            action: "busca_operador_by_nome",
                            texto: texto,
                            id_coordenadoria
                        },
                    })
                    .done(function(result) {

                        $("#result_busca_operador").html("");

                        let c = result.split('<div style="display: none;">inicio_page_ajax</div>')

                        let d = c[2].split('<div style="display: none;">fim_page_ajax</div>')
                        var res = d[0]

                        let my_json = JSON.parse(res);

                        if (my_json.num_rows !== 0) {

                            let operadores = my_json['res']

                            for (let i in operadores) {
                                $("#result_busca_operador").append(
                                    '<div><a href="<?= URL ?>/cidadao/cidadao/' + operadores[i]["id_updated_by"] + '" style="text-decoration: none">' + operadores[i]["name_updated_by"] + '</a></div>'
                                )
                            }
                        }
                        if (my_json.num_rows === 0) {
                            $("#result_busca_operador").text("Nenhum resultado.")
                            // $("#result_busca_operador").text(my_json['num_rows'])
                        }
                    })
                    .fail(function() {
                        // alert("error");
                    })
                    .always(function() {
                        // alert("complete");
                    });

            } else {
                $("#result_busca_operador").html("");
                $("#div_result_busca_operador").hide()

            }
        })

        // $("#input_busca").blur(() => {
        //     $("#input_busca").val("");
        //     $("#result_busca").html("");
        //     $("#div_result_busca").hide()
        // })
    })
</script>