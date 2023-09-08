<?php


?>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');

    .card_chat {
        width: 300px;
        border-radius: 0px 0px 15px 15px;
    }

    .adiv {
        background: #04CB28;
        border-radius: 15px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        height: 46px;
        width: 300px;
        position: fixed;
    }

    .chat {
        border: none;
        background: #E2FFE8;
        font-size: 12px;
        border-radius: 20px;
    }

    .bg-white-chat {
        border: 1px solid #E7E7E9;
        font-size: 12px;
        border-radius: 20px;
    }

    .myvideo img {
        border-radius: 20px
    }

    .dot {
        font-weight: bold;
    }

    .form-control-chat {
        border-radius: 12px;
        border: 1px solid #C0C0C0;
        font-size: 12px;
    }

    .form-control-chat:focus {
        box-shadow: none;
    }

    .form-control-chat::placeholder {
        font-size: 12px;
        color: #C4C4C4;
    }

    .nome-part {
        font-size: 12px;
    }

    .page_operacao {
        font-size: 20;
    }

    .cabecalho {
        background: #04CB28;
        border-radius: 15px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .btn_fechar {
        width: 40px;
        height: 40px;
        background: #04CB28;
        font-size: 25px;
        border-radius: 0px 15px 0px 0px;
    }
</style>

<div class="container">

    <div class="card_chat position-fixed bottom-0 end-0 p-3">
        <!-- Btn Iniciar Conversas -->
        <div class="cabecalho d-flex justify-content-center" id="btn_iniciar">
            <a class="btn btn-outline-light border-0 mw-100" id="btn_conversas" onclick="btn_conversas()" style="height: 40px; background: #04CB28;" title="Minimizar">CONVERSAS</a>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" id="div_chat" style="z-index: 20;">

        <!-- Btns Page -->
        <div class="cabecalho d-flex justify-content-end" id="btns_page">
            <a class="btn text-white" id="btn_minimizar" onclick="btn_minimizar()" style="width: 40px; height: 40px; background: #04CB28; font-size: 25px;" title="Minimizar"><b>-</b></a>
            <a class="btn" id="btn_maximizar" onclick="btn_maximizar()" style="width: 40px; height: 40px; background: #04CB28; font-size: 25px; margin-right: 5px; display: none;" title="Maximizar"><b>&#9723;</b></a>
            <a class="btn text-white btn_fechar" id="btn_fechar" onclick="btn_fechar()" title="Fechar">x</a>
        </div>


        <!-- DIV DO CHAT -->
        <div class="card card_chat bg-light">

            <!-- Div Conversas -->
            <div id="div_conversas col-12">

                <div class="d-flex align-content-end flex-wrap" id="chat_conversas" style="overflow: auto; height: 500px;">

                    <!-- <div class="p-1 col-12">
                        <div class="d-flex justify-content-start p-2 nome-part"><b>Outro part.</b></div>
                        <div class="d-flex justify-content-start">
                            <div class="bg-white-chat ml-2 p-3 col-10">Conversa de ouro participante</div>
                        </div>
                    </div>

                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-end p-2 nome-part text-primary"><b>Eu</b></div>
                        <div class="d-flex justify-content-end">
                            <div class="chat ml-2 p-3 col-10">Minha conversa debaixo</div>
                        </div>
                    </div>

                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-start p-2 nome-part"><b>Outro part.</b></div>
                        <div class="d-flex justify-content-start">
                            <div class="bg-white-chat ml-2 p-3 col-10">Conversa de ouro participante</div>
                        </div>
                    </div>

                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-end p-2 nome-part text-primary"><b>Eu</b></div>
                        <div class="d-flex justify-content-end">
                            <div class="chat ml-2 p-3 col-10">Minha conversa debaixo</div>
                        </div>
                    </div>
                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-start p-2 nome-part"><b>Outro part.</b></div>
                        <div class="d-flex justify-content-start">
                            <div class="bg-white-chat ml-2 p-3 col-10">Conversa de ouro participante</div>
                        </div>
                    </div>

                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-end p-2 nome-part text-primary"><b>Eu</b></div>
                        <div class="d-flex justify-content-end">
                            <div class="chat ml-2 p-3 col-10">Minha conversa debaixo</div>
                        </div>
                    </div>
                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-start p-2 nome-part"><b>Outro part.</b></div>
                        <div class="d-flex justify-content-start">
                            <div class="bg-white-chat ml-2 p-3 col-10">Conversa de ouro participante</div>
                        </div>
                    </div>

                    <div class="p-1 col-12">
                        <div class="d-flex justify-content-end p-2 nome-part text-primary"><b>Eu</b></div>
                        <div class="d-flex justify-content-end">
                            <div class="chat ml-2 p-3 col-10">Última</div>
                        </div>
                    </div> -->

                </div>

                <div class="form-group flex-row mt-3 mb-3 px-3" id="div_send">
                    <textarea class="form-control-chat" id="text-area-chat" style="width:87%; float:left;" rows="2" placeholder="Type your message"></textarea>
                    <button class="btn" type="submit" id="enviar_msg" style="width:13%; float:left;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                        </svg>
                    </button>
                </div>

                <input type="hidden" name="name" id="name" value="<?= $_SESSION['user']['nome'] ?>">

            </div>

        </div>
    </div>

    <!-- testando -->
    <form id="form1">
        <!-- <input type="text" name="name" id="name" placeholder="Digite seu nome">
        <input type="text" name="message" id="message" placeholder="Sua mensagem..."> -->
    </form>

    <!-- <button id="btn_env">Enviar</button> -->

    <hr>
    <div id="content"></div>

    <script>
        var conn = new WebSocket('ws://localhost:8080');

        conn.onopen = function(e) {
            // console.log("Connection established")
        }

        conn.onmessage = function(e) {
            showMessages('other', e.data);
        }

        var form = document.getElementById("form1");
        var inp_message = document.getElementById("text-area-chat");
        var inp_name = document.getElementById("name");
        var btn_env = document.getElementById("enviar_msg");
        var area_content = document.getElementById("chat_conversas");

        

        btn_env.addEventListener('click', function() {

            if (inp_message.value != '') {
                var msg = {
                    'name': inp_name.value,
                    'msg': inp_message.value
                };

                msg = JSON.stringify(msg);

                conn.send(msg);

                showMessages('me', msg);

                inp_message.value = '';
            }
        })

        function showMessages(how, data) {

            var msg = JSON.parse(data);
            console.log(msg);
            var div = document.createElement('div');
            div.setAttribute('class', 'p-3');

            if (how === 'me') {
                area_content.innerHTML +=
                    '<div class="col-12 p-1">' +
                    '<div class="d-flex justify-content-end p-2 nome-part text-success"><b>Eu</b></div>' +
                    '<div class="d-flex justify-content-end">' +
                    '<div class="chat ml-2 p-3 col-10">' + msg.msg + '</div>' +
                    '</div>' +
                    '</div>';
            } else {
                area_content.innerHTML +=
                    '<div class="col-12 p-1">' +
                    '<div class="d-flex justify-content-start p-2 nome-part text-primary"><b>'+ msg.name +'</b></div>' +
                    '<div class="d-flex justify-content-start">' +
                    '<div class="bg-white-chat ml-2 p-3 col-10">' + msg.msg + '</div>' +
                    '</div>' +
                    '</div>'
            }

            // div_send.scrollIntoView();

        }
    </script>

</div>

<script>
    //Coloca sempre na tela atual da Div do Chat o campo de saida de mensagem e as últimas mensagens.
    var div_send = document.getElementById("div_send");
    div_send.scrollIntoView();

    // $("#")

    //Minimizar tela
    function btn_minimizar() {
        $("#div_conversas").hide()
        $("#btn_minimizar").hide()
        $("#btn_maximizar").show()
    }
    //Maximizar tela
    function btn_maximizar() {
        $("#div_conversas").show()
        $("#btn_minimizar").show()
        $("#btn_maximizar").hide()
        div_send.scrollIntoView()
    }

    //Fechar tela
    function btn_fechar() {
        $("#div_chat").hide()
        $("#div_btn_iniciar").show()
    }

    //Btn iniciar chat
    function btn_conversas() {
        $("#div_chat").show()
        $("#div_btn_iniciar").hide()

        btn_maximizar()
    }




    // $(document).ready(() => {

    // })

    //  window.onload = function() {
    // location.href = location.href + '#div_send';
    // function scrolldiv() {
    // var elem = document.getElementById("div_send");
    // elem.scrollIntoView();
    // }
    //   }
</script>