<style>
    .div_chat {
        height: 100vh;
        border: 1px green solid;
        background-color: #f1f2f4;
        border-radius: 8px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        padding: 6px 0px 0px 0px
    }

    .div_send {
        border-radius: 0px 0px 8px 8px;
        /* background-color: white; */
        font-size: 14px;
        padding: 5px;
        font-weight: 500;
    }

    .msg_outro {
        border-radius: 32px;
        font-size: 14px;
        padding: 5px;
        font-weight: 500;
    }

    .msg_eu {
        border-radius: 32px;
        font-size: 14px;
        padding: 5px;
        font-weight: 500;
        background-color: #ADD8E6;
    }

    .dt_hr_quem {
        font-size: 10px;
        padding: 5px;
        border-radius: 5px;
        font-weight: 500;
    }
</style>

<input type="hidden" id="pag_chat" value="pag_chat">

<div class="container mt-5">

    <div class="div_chat row col-xs-12 col-sm-12 col-md-6 col-lg-4 mx-auto">

        <!-- CONVERSA -->
        <div id="conversa" style="display: none;">

            <div style="height:10vh;">

                <div class="row">
                    <div class="col-2 d-flex justify-content-start">
                        <a href="<?= URL ?>/chat/chat" class="btn btn-outline-secondary border-0">
                            <i class="h4 bi bi-arrow-left"></i>
                        </a>
                    </div>

                    <!-- Inputs invisiveis -->
                    <div id="nome_conversando_com" class="col-8 text-center"></div>
                    <input type="hidden" id="id_conversando_com">
                    <input type="hidden" id="id_chat_salas">

                    <div class="div_btn d-flex justify-content-end col-2">


                        <!-- <a href="<?= $dados['home'] ?>" class="btn btn-outline-secondary border-0" style="margin-left: 5px;">
                            <i class="h4 bi bi-house-door"></i>
                        </a> -->

                    </div>

                </div>

                <hr>

            </div>


            <div id="pag_conversas">

                <input type="hidden" id="id_user" value="<?= $dados['id_user'] ?>">
                <input type="hidden" id="name_user" value="<?= $dados['name'] ?>">

                <div class="d-flex" style="height:75vh;">

                    <div class="align-items-end overflow-scroll col-12" style="width:100;">

                        <div id="div_mensagens"></div>

                        <div id="posicionador"></div>

                    </div>

                </div>

                <div class="div_send col-12 mt-2" style="height:10vh;" id="div_send">

                    <textarea class="" name="msg_send" id="text-area-chat" maxlength="200" style="width: 100%; border-radius: 5px;"></textarea>

                </div>

            </div>

        </div>

        <!-- SALAS -->
        <div id="salas">

            <!-- Cabeçalho -->
            <div class="row col-12" style="height: 5vh;">

                <div class="col-2 d-flex justify-content-start">
                    <a href="<?= $dados['home'] ?>">
                        <i class="h4 text-secondary bi bi-arrow-left"></i>
                    </a>
                </div>

                <div class="col-8 d-flex justify-content-center">
                    <h4 class="text-success">CONVERSAS</h4>
                </div>

                <div class="col-2 d-flex justify-content-end">
                    <a href="<?= $dados['home'] ?>" class="btn btn-outline-success">Sair</a>
                </div>

            </div>

            <hr>

            <!-- LISTA DE OPERADORES DO SISTEMA -->
            <div style="height: 80vh; overflow: scroll;">

                <?php if ($dados['users'] != '') {

                    foreach ($dados['users'] as $us) {

                        if ($us['id'] != $_SESSION['user']['id']) { ?>

                            <div class="row col-12 p-2">

                                <?php if ($us['leitura'] == 'Aguardando') { ?>
                                    <div class="col-auto text-warning" id="existe_msg<?= $us['id'] ?>" title="Há novas mensagens"><i class="bi bi-circle"></i></div>
                                <?php } else { ?>
                                    <div class="col-auto" id="existe_msg<?= $us['id'] ?>" title="Não há novas mensagens"><i class="bi bi-circle"></i></div>
                                <?php } ?>
                                <?php  ?>

                                <div class="col-auto">
                                    <a href="javascript:select_user('<?= $us['id'] ?>')" id="user<?= $us['id'] ?>" data-nome="<?= $us['nome'] ?>" data-id_sala="<?= $us['id_sala'] ?>" class="text-decoration-none text-dark"><?= $us['nome'] ?></a>
                                </div>
                            </div>
                <?php }
                    }
                } ?>

            </div>

        </div>

    </div>



    <script>
        const element_visivel = document.getElementById("posicionador");
        element_visivel.scrollIntoView()

        var conn = new WebSocket('wss://191.101.18.29:8080');

        conn.onopen = function(e) {
            console.log("Connection established")
        }

        conn.onmessage = function(e) {
            let dados = e.data;
            let dadosx = JSON.parse(dados);
            let acao = dadosx.acao;

            showMessages(acao, dados);

        }

        var form = document.getElementById("form1");
        var inp_message = document.getElementById("text-area-chat");
        var id_user = document.getElementById("id_user");
        var inp_name = document.getElementById("name_user");
        var btn_env = document.getElementById("enviar_msg");
        var area_content = document.getElementById("div_mensagens");
        var id_conversando_com = document.getElementById("id_conversando_com");
        var nome_conversando_com = document.getElementById("nome_conversando_com");
        var id_sala = document.getElementById("id_chat_salas");

        // Enviando mensagem
        inp_message.addEventListener("keypress", function(event) {

            if (event.key === "Enter") {
                event.preventDefault();

                if (inp_message.value != '') {
                    var msg = {
                        'id_user': id_user.value,
                        'name_user': inp_name.value,
                        'msg': inp_message.value,

                        // id_receptor
                        'id_conversando_com': id_conversando_com.value,

                        'nome_conversando_com': nome_conversando_com.textContent,
                        'id_sala': id_sala.value,
                        'acao': 'apenas_msg',
                        'id_emissor': id_user.value
                    };

                    msg = JSON.stringify(msg);

                    conn.send(msg);

                    // showMessages('me', msg);

                    inp_message.value = '';
                }
            }
        });

        //Mostrando mensagens
        function showMessages(how, data) {

            var msg = JSON.parse(data);

            var div = document.createElement('div');
            div.setAttribute('class', 'p-3');

            $("#conversandocom").val(msg.nome_conversando_com);

            if (id_sala.value == "") {
                id_sala.value = msg.id_sala;
            }
            if (how === 'apenas_msg') {

                // Notificando que há mensagem
                if (msg.id_receptor === id_user.value) {
                    $("#existe_msg" + msg.id_emissor).addClass("text-warning");
                }

                if (id_sala.value === msg.id_sala) {

                    if (id_user.value === msg.id_user) {
                        area_content.innerHTML +=

                            '<div class="div_eu d-flex justify-content-end mb-3" id="eu" style="position: relative;">' +
                            '<div class="col-10">' +
                            '<div class="dt_hr_quem row mt-2">' +
                            '<div class="col-auto"><b>EU ' + msg.data_hora + '</b></div>' +
                            '</div>' +
                            '<div class="msg_eu card col-12">' +
                            '<div class="card-body">' +
                            msg.msg +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                    } else {


                        area_content.innerHTML +=
                            '<div class="div_outro" id="other" style="position: relative;">' +
                            '<div class="dt_hr_quem row mt-2">' +
                            '<div class="col-auto"><b>' + msg.nome_conversando_com + ' ' + msg.data_hora + '</b></div>' +
                            '</div>' +
                            '<div class="msg_outro card col-10">' +
                            '<div class="lida card-body">' +
                            msg.msg +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        retorno_msg(msg.id_sala)

                    }



                }
            } else {

                if (!msg.mesages || msg.mesages === "") {
                    // alert("sala sem conversa");
                } else {
                    let msgs = msg.mesages;
                    // alert(msgs[0].id_emissor)
                    let id_user_local = id_user.value;

                    if (msg.id_user == id_user.value) {

                        for (let i = 0; i < msgs.length; i++) {

                            if (msgs[i].id_emissor == id_user_local) {
                                console.log($("#nome_conversando_com").text());

                                let nome_conversando_com2 = $("#nome_conversando_com").text();
                                area_content.innerHTML +=

                                    '<div class="div_eu d-flex justify-content-end mb-3" id="eu" style="position: relative;">' +
                                    '<div class="col-10">' +
                                    '<div class="dt_hr_quem row mt-2">' +
                                    '<div class="col-auto"><b>EU ' + msgs[i].data_hora + '</b></div>' +
                                    '</div>' +
                                    '<div class="msg_eu card col-12">' +
                                    '<div class="card-body">' +
                                    msgs[i].msg +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            } else {
                                area_content.innerHTML +=
                                    '<div class="div_outro" id="other" style="position: relative;">' +
                                    '<div class="dt_hr_quem row mt-2">' +
                                    '<div class="col-auto"><b>' + $("#nome_conversando_com").text() + ' ' + msgs[i].data_hora + '</b></div>' +
                                    '</div>' +
                                    '<div class="msg_outro card col-10">' +
                                    '<div class="card-body">' +
                                    msgs[i].msg +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';


                            }
                        }

                    }
                }

            }

            const element_visivel = document.getElementById("posicionador");
            element_visivel.scrollIntoView()

        }

        //Selecionar usuário para conversa
        function select_user(id) {

            area_content.innerHTML = "";

            // Dados do outro
            let user_chat = $("#user" + id);

            let nome = user_chat.data("nome");
            let id_sala_user = user_chat.data("id_sala");

            // let id = $("#select_user").val();
            // let nome = $("#select_user :selected").text();
            // let id_sala = $("#select_user").find(':selected').data('id_sala');

            $("#salas").hide();
            $("#conversa").show();

            $("#nome_conversando_com").text(nome);
            $("#id_conversando_com").val(id);
            $("#id_chat_salas").val(id_sala_user);

            let msg = {
                'id_sala': id_sala_user,
                'id_user': id_user.value,
                'name_user': inp_name.value,
                'id_conversando_com': id, //id conversando com...
                'nome_conversando_com': nome, // nome conversando com
                'acao': 'pegar_conversa'
            }

            msg = JSON.stringify(msg);

            conn.send(msg);

        }

        function retorno_msg(id_sala) {

            if ($("#conversa").is(":visible")) {

                setTimeout(function() {

                    let msg = {
                        'id_sala': id_sala,
                        'acao': 'Lida'
                    }

                    msg = JSON.stringify(msg);

                    conn.send(msg);
                }, 2000);
            }
        }

        function home_chat() {
            $("#conversa").hide();
            $("#salas").show();
        }
    </script>

</div>