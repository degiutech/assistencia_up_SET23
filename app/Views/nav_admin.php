<?php

$user = $_SESSION['user'];

?>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL ?>/admin">HOME</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Assistências -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Assistências
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- <li><a class="dropdown-item" href="<?= URL ?>/admin/assistencias">Gerenciar</a></li> -->
                        <li><a class="dropdown-item" href="<?= URL ?>/assistencias/filtro_geral">Geral</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/assistencias/filtro_coordenadoria">Por Coordenadoria</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/assistencias/filtro_operador">Por Operador</a></li>
                    </ul>
                </li>

                <!-- Cidadão -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Cidadão
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>/cidadao/create">Novo Cidadão</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/cidadao/cadastros_recentes">Cadastros Recentes</a></li>
                    </ul>
                </li>

                <!-- Coordenação -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Coordenadorias
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/all_coordenadorias">Listar Coordenadorias</a></li>
                        <!-- <li><a class="dropdown-item" href="<?= URL ?>/admin/all_coordenadores">Listar Coordenadores</a></li> -->
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/create_coordenadoria">Nova Coordenadoria</a></li>
                    </ul>
                </li>

                <!-- Operadores -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Operadores
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>/users/create">Novo Operador</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/all_operadores">Todos os Operadores</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/all_supervisores">Supervisores</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/all_coordenadores">Coordenadores</a></li>
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/all_representantes">Assessores</a></li>
                    </ul>
                </li>

                <!-- Meus Registros -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Meus Registros
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= URL ?>/admin/minhas_assistencias">Minhas Assistências</a></li>
                    </ul>
                </li>

                <!-- Mensagens -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?= URL ?>/mensagem">
                        Mensagens
                    </a>
                </li>

                <!-- FAQ -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?= URL ?>/faq/faq">
                        FAQ
                    </a>
                </li>

                <!-- Conversas -->
                <!-- <li class="nav-item">
                    <a class="nav-link active" href="<?= URL ?>/chat/chat">
                        Conversas
                    </a>
                </li> -->

            </ul>

            <!-- Busca -->
            <div class="d-flex" role="search">
                <div>
                    <input class="form-control me-2" type="search" id="input_busca" placeholder="Busque um Cidadão" aria-label="Search">

                    <div class="card" id="div_result_busca" style="position: absolute; z-index: 10; width: 235px; display:none;">

                        <div class="card-body" id="result_busca">

                        </div>
                    </div>
                </div>
            </div>


            <form class="d-flex">

                <?php if (isset($_SESSION['user'])) { ?>

                    <div class="dropdown">
                        <button class="btn text-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                            Olá, <?= $user['nome'] ?>!
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li><a href="<?= URL ?>/users/logout" class="dropdown-item">Sair</a></li>
                            <li><a href="<?= URL ?>/users/meus_dados" class="dropdown-item">Meus Dados</a></li>
                        </ul>
                    </div>

                <?php } else { ?>

                    <a href="<?= URL ?>/users/login_email" class="nav-link text-secondary">Entrar</a>

                <?php } ?>

            </form>
        </div>
    </div>

</nav>

<div class="container">
    <h1>Administração</h1>
    <hr>
</div>



<script>
    $(document).ready(function() {

        $("#input_busca").keyup(() => {

            let url = "<?= URL ?>/ajax/busca_cidadao"

            let texto = $("#input_busca").val()
            if (texto.length >= 3) {
                $("#div_result_busca").show()

                $.ajax({
                        method: "POST",
                        url: url,

                        data: {
                            action: "busca_cidadao",
                            texto: texto
                        },
                    })
                    .done(function(result) {

                        $("#result_busca").html("");

                        let c = result.split('<div style="display: none;">inicio_page_ajax</div>')

                        let d = c[2].split('<div style="display: none;">fim_page_ajax</div>')
                        var res = d[0]

                        let my_json = JSON.parse(res);

                        if (my_json.num_rows !== 0) {

                            let cidadaos = my_json['res']

                            for (let i in cidadaos) {
                                $("#result_busca").append(
                                    '<div><a href="<?= URL ?>/cidadao/cidadao/' + cidadaos[i]["id"] + '" style="text-decoration: none">' + cidadaos[i]["nome"] + '</a></div>'
                                )
                            }
                        }
                        if (my_json.num_rows === 0) {
                            $("#result_busca").text("Nenhum resultado.")

                        }
                    })
                    .fail(function() {
                        // alert("error");
                    })
                    .always(function() {
                        // alert("complete");
                    });

            } else {
                $("#result_busca").html("");
                $("#div_result_busca").hide()

            }
        })

        // $("#input_busca").blur(() => {
        //     $("#input_busca").val("");
        //     $("#result_busca").html("");
        //     $("#div_result_busca").hide()
        // })
    })
</script>