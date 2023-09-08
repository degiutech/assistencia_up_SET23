
function testeAgora() {
    alert("funções")
}


//Máskaras para inputs
$(document).ready(function () {

    $('.telefone').mask('(00) 0 0000-0000');
    $('.celular').mask('(00) 00000-0000');
    $('.dinheiro').mask('###0,00', { reverse: true });
    $('.dinheiro2').mask('#.##0,00');
    $('.estado').mask('AA');
    $('.cpf').mask('000.000.000-00');
    // $('.cnpj').mask('00.000.000/0000-00');
    $('.rg').mask('00.000.000-0');
    $('.sus').mask('000 0000 0000 0000');
    $('.cep').mask('00000-000');
    $('.dataNascimento').mask('00/00/0000');
    $('.placaCarro').mask('AAAAAAA');
    $('.horasMinutos').mask('00:00');
    $('.cartaoCredito').mask('0000 0000 0000 0000');
    $(".anoModelo").mask('00/00');
    $(".procJuridico").mask('0000000-00.0000.0.00.0000');

    //Da documentação
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    //  $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', { reverse: true });
    $('.cnpj').mask('00.000.000/0000-00', { reverse: true });
    $('.money').mask('000.000.000.000.000,00', { reverse: true });
    $('.money2').mask("#.##0,00", { reverse: true });
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', { reverse: true });
    $('.clear-if-not-match').mask("00/00/0000", { clearIfNotMatch: true });
    $('.placeholder').mask("00/00/0000", { placeholder: "__/__/____" });
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
                pattern: /[\/]/,
                fallback: '/'
            },
            placeholder: "__/__/____"
        }
    });
    $('.selectonfocus').mask("00/00/0000", { selectOnFocus: true });
});

function apoioEnderecoViaCep() {

    var cep = $("#cep").val();

    if (cep.length < 9) {

        $("#btn-register").prop("disabled", true);
        $("#logradouro").val("");
        $("#complemento").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");

    }

}

function enderecoViaCep() {
    $("#cep_feedback").html("");

    var cep = $("#cep").val();

    if (cep.length > 0 && cep.length < 9) {

        $("#cep_feedback").text("CEP incompleto.")
        $("#btn-register").prop("disabled", true);

        $("#logradouro").val("");
        $("#complemento").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
    }
    if (cep.length === 0) {
        $("#btn-register").prop("disabled", false);

        $("#logradouro").val("");
        $("#complemento").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
    }

    //Início do Comando AJAX
    if (cep.length === 9) {

        $.ajax({
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            dataType: 'json',
            success: function (resposta) {

                if (resposta.erro) {
                    $("#cep_feedback").text("CEP inválido!")
                    $("#logradouro").val("");
                    $("#complemento").val("");
                    $("#bairro").val("");
                    $("#cidade").val("");
                    $("#uf").val("");
                    $("#ibge").val("");
                    $("#btn-register").prop("disabled", true);

                } else {

                    $("#cep_feedback").text("")
                    $("#logradouro").val(resposta.logradouro);
                    $("#complemento").val(resposta.complemento);
                    $("#bairro").val(resposta.bairro);
                    $("#cidade").val(resposta.localidade);
                    $("#uf").val(resposta.uf);
                    $("#ibge").val(resposta.ibge);

                    $("#btn-register-endereco").prop("disabled", false);
                    $("#btn-register").prop("disabled", false);


                    $("#numero").focus();

                }
            }
        });

    }

}

function pular() {
    let confirma = confirm("Você pode ver isto depois.")
    if (confirma) {
        $("#btn-register-endereco").click()
    } else {
        return
    }
}


//FORMULÁRIOS DE CONFIGURAÇÕES DE USUÁRIOS

function InputCheckAcesso(id) {

    if (id === 'check_admin' || id === 'check_super' || id === 'check_rep') {
        $("#btn-register").val("Enviar")
        $("#div_coordenadorias").hide()
        // $("#div_coordenadorias_rep").hide()
        $("#div_nova_coordenadoria").hide()
        // $("#div_bloqueio").hide()

    }
    if (id === "check_coord") {
        $("#btn-register").val("Enviar")
        $("#div_coordenadorias").show()
        // $("#div_coordenadorias_rep").hide()

    }
    // if (id === 'check_rep') {
    //     $("#btn-register").val("Continuar")
    //     $("#div_coordenadorias").hide()
    //     $("#div_nova_coordenadoria").hide()
    //     $("#div_coordenadorias_rep").show()

    // }
}

function inputNovaCoordenadoria() {
    let vr = $("#select_coordenadoria").val()

    if (vr === "nova") {
        $("#div_nova_coordenadoria").show()
        $("#input_nova_coordenadoria").focus()
    }
    if (vr !== "nova") {
        $("#div_nova_coordenadoria").hide()
        $("#input_nova_coordenadoria").val("")

        //Setar o primeiro option
        // $("#select_coordenadoria").val($("#select_coordenadoria option:first").val());

    }

}

function confirm_bloqueio_op() {

    let vr = $("#select_bloqueio").val()

    if (vr === "Desbloqueado") {
        let confirma = confirm("Desbloquear este operador??")
        if (confirma) {
            $("#select_bloqueio").val("Desbloqueado").css("color", "green")
        } else {
            $("#select_bloqueio").val("Bloqueado").css("color", "red")
        }
    }
    if (vr === "Bloqueado") {
        let confirma = confirm("Bloquear este operador??")
        if (confirma) {
            $("#select_bloqueio").val("Bloqueado").css("color", "red")
        } else {
            $("#select_bloqueio").val("Desbloqueado").css("color", "green")

        }
    }

}

function forcaSenha() {
    var forca = 0;
    var pass = $("#pass").val();
    var t = pass.length;
    $("#t").text(t);
    if (pass.length === 0) {
        $("#div-barra").hide();
        $("#forca-senha").hide();
    } else if (pass.length > 0) {
        $("#div-barra").show();
        $("#forca-senha").show();
    }

    if (pass.length >= 4) {
        forca += 10;
    }
    if (pass.length > 7) {
        forca += 25;
    }
    if ((pass.length >= 5) && (pass.match(/[a-z]+/))) {
        forca += 10;
    }
    if ((pass.length >= 6) && (pass.match(/[A-Z]+/))) {
        forca += 20;
    }
    if ((pass.length >= 8) && (pass.match(/[@#$%&;:*]+/))) {
        forca += 25;
    }
    mostrarForcaSenha(forca, pass);
}

function mostrarForcaSenha(forca, pass) {
    $("#mostrar").text(forca);
    if (forca < 30) {
        $("#forca-senha").html("<span style='color: #ff0000' >Senha fraca</span>");
        $("#barra-forca").css({ "width": "25%", "background-color": "#ff0000" });
    } else if ((forca >= 30) && (forca < 50)) {
        $("#forca-senha").html("<span style='color: #FFD700' >Senha média</span>");
        $("#barra-forca").css({ "width": "50%", "background-color": "#FFD700" });
    } else if ((forca >= 50) && (forca < 70)) {
        $("#forca-senha").html("<span style='color: #7FFF00' >Senha forte</span>");
        $("#barra-forca").css({ "width": "75%", "background-color": "#7FFF00" });
    } else if ((forca >= 70) && (forca < 100)) {
        $("#forca-senha").html("<span style='color: #008000' >Senha excelente</span>");
        $("#barra-forca").css({ "width": "100%", "background-color": "#008000" });
    }
}
