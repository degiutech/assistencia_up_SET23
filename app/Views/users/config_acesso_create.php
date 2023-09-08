

<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-8">

        <h3>Configurar Acesso de Novo Operador</h3>

        <div class="card">
            <div class="card-body">

                <?= Sessao::mensagem('user') ?>

                <form method="post" name="config_op" action="<?= URL ?>/users/config_acesso_create">

                    <div class="mb-3">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="acesso">Acesso:</label>

                        <?php if ($dados['permissao'] == 'Administração') { ?>

                            <div class="col-12 mb-1 mt-1" style="border: 1px #DCDCDC solid; padding: 15px;">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="check_acesso" id="check_admin" value="Administração" onclick="check_acesso_create(this.id)">
                                    <label class="form-check-label" for="check_admin">
                                        Administração
                                    </label>
                                </div>

                            </div>

                            <div class="col-12 mb-1" style="border: 1px #DCDCDC solid; padding: 15px;">


                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="check_acesso" id="check_super" value="Supervisão" onclick="check_acesso_create(this.id)">
                                    <label class="form-check-label" for="check_super">
                                        Supervisão
                                    </label>
                                </div>

                            </div>

                        <?php } ?>

                        <!-- Div das Coordenadorias -->
                        <div class="col-12 mb-1" style="border: 1px #DCDCDC solid; padding: 15px;">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="check_acesso" id="check_coord" <?= ($dados['acesso'] == 'Coordenadoria') ? 'checked' : '' ?> value="Coordenadoria" onclick="check_acesso_create(this.id)">
                                <label class="form-check-label" for="check_coord">
                                    Coordenador
                                </label>
                            </div>

                            <!-- Coordenadorias -->
                            <div class="row" id="todas_coordenadorias" style="display: none;">

                                <div class="col-auto mb-2" id="div_coordenadorias">

                                    <label for="select_coordenadoria">Selecione uma Coordenadoria:</label>
                                    <!-- onchange="inputNovaCoordenadoria()" -->
                                    <select class="form-select" name="coordenadoria" id="select_coordenadoria" onchange="select_coordenadoria_create()" aria-label="Default select example">

                                        <option value="Selecione" selected id="selecione">Selecione</option>

                                        <?php if (isset($dados['coordenadorias'])) {
                                            foreach ($dados['coordenadorias'] as $coord) { ?>

                                                <option value="<?= $coord['id'] ?>" <?= ($dados['id_coordenadoria'] == $coord['id']) ? 'selected' : '' ?>><?= $coord['nome'] ?></option>

                                        <?php }
                                        } ?>

                                        <option value="nova" id="nova" <?= ($dados['id_coordenadoria'] == 'nova') ? 'selected' : '' ?> style="color:green">Criar nova Coordenadoria</option>

                                    </select>
                                </div>

                                <div class="col-auto mb-2" id="div_nova_coordenadoria">

                                    <label for="nova_coordenadoria">Crie Nova Coordenadoria:</label>
                                    <input type="text" id="input_nova_coordenadoria" name="input_nova_coordenadoria" onkeyup="crie_nova_coordenadoria()" value="<?= $dados['nova_coordenadoria'] ?>" class="form-control" minlength="4" maxlength="30">
                                </div>

                            </div>

                        </div>

                        <div class="col-12 mb-1" style="border: 1px #DCDCDC solid; padding: 15px;">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="check_acesso" id="check_rep" value="Representante" onclick="check_acesso_create(this.id)">
                                <label class="form-check-label" for="check_rep">
                                    Assessoria
                                </label>
                            </div>

                        </div>

                    </div>


                    <!-- Botões -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= URL ?>/users/create" class="btn btn-outline-info">Cancelar</a>
                                <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Enviar">
                            </div>
                            <div class="col-md-6">
                                <!-- <a href="<?= URL ?>/users/login">Já é registrado? Faça o login</a> -->
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    if ($("input[name='check_acesso']:checked").val() === "Coordenadoria") {
        // $("#div_coordenadorias").show()
        // $("#div_nova_coordenadoria").show()
        $("#todas_coordenadorias").show()
    }

    function check_acesso_create(id) {

        if (id === 'check_admin' || id === 'check_super' || id === 'check_rep') {
            $("#btn-register").val("Enviar")
            // $("#div_coordenadorias").hide()
            // $("#div_nova_coordenadoria").hide()
            $("#todas_coordenadorias").hide()

        }
        if (id === "check_coord") {
            $("#btn-register").val("Enviar")
            // $("#div_coordenadorias").show()
            // $("#div_nova_coordenadoria").show()
            $("#todas_coordenadorias").show()


        }
    }

    function crie_nova_coordenadoria() {
        $("#select_coordenadoria").val("nova")
    }

    function select_coordenadoria_create() {

        if ($("#select_coordenadoria").val() !== "nova") {
            $("#input_nova_coordenadoria").val("")
        }

    }

    // function js_coordenadorias(id) {

    //     if (id === "nova") {
    //         $("#div_coordenadorias").show()
    //         $("#div_nova_coordenadoria").show()
    //     } else {
    //         $("#div_coordenadorias").hide()
    //         $("#div_nova_coordenadoria").hide()
    //     }
    // }

    // $(document).ready(function() {

    // alert($("input[name='check_acesso']:checked").val())



    //     if ($("#nova").attr("selected")) {



    //     }
    // })
</script>