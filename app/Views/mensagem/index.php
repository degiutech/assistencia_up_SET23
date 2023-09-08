<?php

$id_outro = '';
$nome_outro = '';
if (isset($dados['id_outro']) && isset($dados['nome_outro'])) {
    $id_outro = $dados['id_outro'];
    $nome_outro = $dados['nome_outro'];
}

?>

<script>
    var array_id_outro = [];
</script>

<input type="hidden" value="<?= $dados['meu_id'] ?>" id="meu_id">


<div class="container">
    <h3>Caixa de Mensagens</h3>

    <button class="btn btn-outline-primary mb-3" onclick="list_part()">
        <span id="part_esconder"><i class="bi bi-arrow-left-short"></i></span>
        Participantes
        <span id="part_mostrar" style="display: none;"><i class="bi bi-arrow-right-short"></i></span>
    </button>

    <div class="card" id="div_inatividade" style="display: none;">
        <div class="card-body">
            <h3>Caixa de Mensagens encerrada por inatividade.</h3>
            <a href="<?= $dados['home'] ?>" class="btn btn-primary">HOME</a>
            <button type="submit" id="btn_retorna_caixa" class="btn btn-success">Abrir novamente</button>
        </div>
    </div>

    <div class="row" id="div_atividade">

        <!-- Lista dos Participantes -->
        <div class="card col-md-3" id="div_participantes">
            <div class="card-body">
                <?php foreach ($dados['users'] as $user) { ?>

                    <div>
                        <form action="<?= URL ?>/mensagem/select_user" method="post">

                            <input type="hidden" class="id_user_outro" id="id_do_outro_<?= $user['id'] ?>" value="<?= $user['id'] ?>" name="id_outro">
                            <input type="hidden" value="<?= $user['nome'] ?>" name="nome_outro">

                            <!-- <input type="text"> -->

                            <script>
                                array_id_outro.push({
                                    "id": "<?= $user['id'] ?>",
                                    "lida": <?= $user['msgs_nao_lidas'] ?>
                                });
                            </script>

                            <span><i class="bi bi-circle-fill text-warning" id="circle_cheio_<?= $user['id'] ?>" style="font-size: 0.7rem; display: none;" title="HÁ MENSAGENS PARA VISUALIZAÇÃO."></i></span>
                            <span><i class="bi bi-circle text-secondary" id="circle_vazio_<?= $user['id'] ?>" style="font-size: 0.7rem;" title="Não há mensagens."></i></span>

                            <input type="submit" value="<?= $user['nome'] ?>" class="input_color btn btn-link text-secondary" style="text-decoration: none;">
                        </form>
                    </div>

                <?php } ?>

            </div>
        </div>

        <!-- DIV DE MENSAGENS -->
        <div class="card col-md-9">
            <div class="card-body">
                <h4 class="cor-texto"><?= $nome_outro ?></h4>

                <!-- Div das memsagens -->
                <div class="col-12" id="div_das_mensagens" style="height: 300px; overflow-y: auto;">

                    <?php if (isset($dados['mensagens']) && $dados['mensagens'] != '') {
                        foreach ($dados['mensagens'] as $msg) {

                            $dh = new DateTime($msg['created_at']);
                            $data_hora = $dh->format('d/m H:i');

                            if ($msg['id_origem'] == $dados['meu_id']) { ?>

                                <div class="col-12 mt-3">
                                    <div style="font-size: x-small;"><b>Eu - <?= $data_hora ?></b></div>
                                    <div class="card" style="background-color: #E7FAED; border-radius: 20px;">
                                        <div class="card-body">
                                            <?= $msg['mensagem'] ?>
                                        </div>
                                    </div>
                                </div>



                            <?php }
                            if ($msg['id_origem'] == $dados['id_outro']) { ?>

                                <div class="col-12 mt-3">
                                    <span style="font-size: x-small;"><b><?= $dados['nome_outro'] ?> - <?= $data_hora ?></b></span>
                                    <div class="card" style="border-radius: 20px;">
                                        <div class="card-body">
                                            <?= $msg['mensagem'] ?>
                                        </div>
                                    </div>
                                </div>

                    <?php }
                        }
                    } ?>

                    <div id="posicionador"></div>

                </div>

                <!-- Textarea da mensagem a ser enviada e botão ENVIAR -->
                <div class="mt-5" id="div_mensagem_send">

                    <form action="<?= URL ?>/mensagem/send" method="post">

                        <input type="hidden" name="id_destino" id="id_outro_send" value="<?= $id_outro ?>">
                        <input type="hidden" name="nome_destino" id="nome_outro_send" value="<?= $nome_outro ?>">

                        <div class="row">
                            <div class="col-10">
                                <textarea class="form-control" name="mensagem" id="text_msg" rows="1" style="width: 100%;"></textarea>
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-success" value="Enviar">
                            </div>
                        </div>

                    </form>

                </div>



            </div>
        </div>

        <button type="submit" class="btn btn-success" id="btn_solicita" style="display: none;">AJAX</button>

    </div>
   
</div>

<script>
    const element_visivel = document.getElementById("posicionador");

    $(document).ready(function() {

        let meu_id = $("#meu_id").val();

        //Marcação na lista de usuários de que há mensagem
        array_id_outro.forEach(function(outro) {
            // console.log(outro.lida);
            if (outro.lida >= 1) {
                $("#circle_vazio_" + outro.id).hide();
                $("#circle_cheio_" + outro.id).show();
            }
        });

        //Mostrar ou esconder a div de texto de saída de mensagem e send.
        let id_outro = $("#id_outro_send").val();
        let nome_outro = $("#nome_outro_send").val();
        if (id_outro.length == "" && nome_outro == "") {
            $("#div_mensagem_send").hide();
        } else {
            $("#div_mensagem_send").show();
        }

        //Solicitação AJAX
        let cont_tempo = 0;
        let solicita_ajax = true;

        if (solicita_ajax) {

            setInterval(function() {

                if (cont_tempo <= 7) {
                    let text_msg = $("#text_msg").val();
                    if (text_msg.length == 0) {
                        //marca a que tem pelo menos uma mensagem não lida por mim
                        $("#btn_solicita").click();
                        cont_tempo += 1;
                    }
                } else {
                    $("#div_atividade").hide();
                    $("#div_inatividade").show();

                }
            }, 30000);
        } else {
            solicita_ajax = false;
        }

        $("#btn_retorna_caixa").click(() => {
            $("#div_atividade").show();
            $("#div_inatividade").hide();
            cont_tempo = 0;
            solicita_ajax = true;
        });

        $("#btn_solicita").click(() => {

            let url = "<?= URL ?>/mensagem/solicita_ajax";

            $.ajax({
                    method: "POST",
                    url: url,

                    data: {
                        action: "users",
                        id_outro: id_outro
                    },
                })
                .done(function(result) {

                    let c = result.split('<div style="display: none;">inicio_page_ajax</div>')

                    let d = c[2].split('<div style="display: none;">fim_page_ajax</div>')
                    var res = d[0]

                    let users = JSON.parse(res);

                    //Percorre os usuários
                    users.forEach(function(user) {

                        if (user.id == id_outro) {

                            //carregar conversa aberta
                            let conversa = user.conversa_aberta;
                            conversa.forEach(function(conv) {

                                //DATA E HORA
                                let dtm = conv.created_at.toString();

                                let nd = dtm.split(' ');

                                let data_split = nd[0];
                                let dbruta = data_split.split('-');
                                let dia = dbruta[2];
                                let mes = dbruta[1];

                                let hora_split = nd[1];
                                let hbruta = hora_split.split(':');
                                let hora = hbruta[0];
                                let min = hbruta[1];

                                let data_hora = dia + "/" + mes + " " + hora + ":" + min;

                                if (conv.id_destino == meu_id) {
                                    $("#div_das_mensagens").append(
                                        '<div class="col-12 mt-3">' +
                                        '<span style="font-size: x-small;"><b>' + nome_outro + ' - ' + data_hora + '</b></span>' +
                                        '<div class="card" style="border-radius: 20px;">' +
                                        '<div class="card-body">' +
                                        conv.mensagem +
                                        '</div>' +
                                        '</div>' +
                                        '</div>'
                                    );

                                    element_visivel.scrollIntoView();

                                    // $("#circle_vazio_" + user.id).show();
                                    // $("#circle_cheio_" + user.id).hide();

                                }

                            });
                        }

                        // //Altera as bolinhas para diferenciar lidas de não lidas
                        if (user.msgs_nao_lidas >= 1 && user.id != id_outro) {
                            $("#circle_vazio_" + user.id).hide();
                            $("#circle_cheio_" + user.id).show();
                        } else {
                            $("#circle_vazio_" + user.id).show();
                            $("#circle_cheio_" + user.id).hide();
                        }
                    })

                })
                .fail(function() {
                    // alert("error");
                })
                .always(function() {
                    // alert("complete");
                });
        })

    })

    function list_part(valor) {

        $("#part_esconder").toggle();
        $("#part_mostrar").toggle();
        $("#div_participantes").toggle();

    }

    function addZero(valor) {
        if (valor.length == 1) {
            valor = "0" + valor;
        }
        return valor;
    }

    element_visivel.scrollIntoView()
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>