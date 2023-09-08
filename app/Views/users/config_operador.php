<div class="container">



    <div class="d-flex justify-content-center">

        <div class="col-md-8">

            <div class="row mb-1">
                <div class="col-md-6">
                    <h3>Configurações de Operador</h3>
                </div>

                <div class="col-md-6 d-flex justify-content-end">
                    <?php if (isset($dados['home'])) { ?>
                        <a href="<?= $dados['home'] ?>" class="btn btn-secondary" style="margin-right: 5px;">HOME</a>
                    <?php } ?>
                    <a href="javascript:window.history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <?= Sessao::mensagem('user') ?>

                    <form method="post" name="config_op" action="<?= URL ?>/users/config_op_acesso">

                        <input type="hidden" name="id" value="<?= $dados['id'] ?>">

                        <div class="mb-3">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" class="form-control" readonly>
                        </div>

                        <?= Sessao::mensagem('user2') ?>

                        <?php if ($dados['permissao'] != 'Coordenadoria') { ?>

                            <div id="div_dados_config">

                                <div class="mb-3">

                                    <?php if ($dados['permissao'] == 'Administração') { ?>

                                        <label for="acesso">Acesso:</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="check_acesso" id="check_admin" value="Administração" <?= ($dados['acesso'] == 'Administração') ? 'checked' : '' ?> onclick="InputCheckAcesso(this.id)">
                                            <label class="form-check-label" for="check_admin">
                                                Administração
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="check_acesso" id="check_super" value="Supervisão" <?= ($dados['acesso'] == 'Supervisão') ? 'checked' : '' ?> onclick="InputCheckAcesso(this.id)">
                                            <label class="form-check-label" for="check_super">
                                                Supervisão
                                            </label>
                                        </div>

                                    <?php }

                                    if ($dados['permissao'] == 'Supervisão' || $dados['permissao'] == 'Administração') { ?>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="check_acesso" id="check_coord" value="Coordenadoria" <?= ($dados['acesso'] == 'Coordenadoria') ? 'checked' : '' ?> onclick="InputCheckAcesso(this.id)">
                                            <label class="form-check-label" for="check_coord">
                                                Coordenador
                                            </label>
                                        </div>

                                        <!-- Coordenadorias -->
                                        <div class="row">

                                            <div class="col-auto mb-2" id="div_coordenadorias" style="display: none;">

                                                <label for="coordenadoria">Selecione a Coordenadoria:</label>

                                                <select class="form-select" name="coordenadoria" id="select_coordenadoria" aria-label="Default select example" onchange="inputNovaCoordenadoria()">

                                                    <?php if (isset($dados['coordenadorias'])) {
                                                        foreach ($dados['coordenadorias'] as $coord) { ?>

                                                            <option value="<?= $coord['id'] ?>" <?= ($dados['id_coordenadoria'] == $coord['id']) ? 'selected' : '' ?>><?= $coord['nome'] ?></option>

                                                    <?php }
                                                    } ?>

                                                    <option value="nova">Criar nova Coordenadoria</option>

                                                </select>
                                            </div>

                                            <div class="col-auto mb-2" id="div_nova_coordenadoria" style="display: none;">

                                                <label for="nova_coordenadoria">Digite a nova Coordenadoria:</label>
                                                <input type="text" id="input_nova_coordenadoria" name="input_nova_coordenadoria" class="form-control">
                                            </div>

                                        </div>

                                    <?php } ?>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="check_acesso" id="check_rep" value="Representante" <?= ($dados['acesso'] == 'Representante') ? 'checked' : '' ?> onclick="InputCheckAcesso(this.id)">
                                        <label class="form-check-label" for="check_rep">
                                            Assessoria
                                        </label>
                                    </div>

                                </div>

                                <!-- Botões -->
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- <a href="<?= URL ?>/users/info_operador/<?= $dados['id'] ?>" class="btn btn-outline-info">Cancelar</a> -->
                                            <a href="javascript:window.history.back()" class="btn btn-outline-info">Cancelar</a>
                                            <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Enviar">
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <a href="<?= URL ?>/users/login">Já é registrado? Faça o login</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    </form>

                    <hr>

                    <!-- Form Bloqueio -->
                    <div class="row">

                        <div class="col-md-6 mt-3">
                            <form action="<?= URL ?>/users/bloqueio_desbloqueio" method="post">

                                <label for="bloqueio" id="label_bloqueio" class="form-label">Acesso <?= $dados['bloqueio'] ?></label>

                                <input type="hidden" name="id" id="id" value="<?= $dados['id'] ?>">
                                <input type="hidden" name="bloqueio" id="bloqueio" value="<?= $dados['bloqueio'] ?>">

                                <div>
                                    <input type="button" class="btn" id="btn_bloqueio" value="" onclick="bloqueioDesbloqueio()">
                                    <input type="submit" class="btn" id="btn_bloqueio_submit" style="display: none;">
                                </div>

                            </form>

                        </div>

                        <div class="d-flex justify-content-md-end col-md-6 mt-3">
                            <div>
                                <label for="ir_operador" class="form-label">Página de Operador</label>
                                <a href="<?= URL ?>/users/info_operador/<?= $dados['id'] ?>" class="form-control btn btn-outline-secondary col-auto">Operador</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {

            let bloqueio = $("#bloqueio").val()
            let btn_bloqueio = $("#btn_bloqueio")
            let label_bloqueio = $("#label_bloqueio")

            btn_bloqueio.removeClass("btn-outline-danger")
            btn_bloqueio.removeClass("btn-outline-success")

            if (bloqueio === "Desbloqueado") {
                label_bloqueio.text("Acesso Desbloqueado").css("color", "green")
                btn_bloqueio.val("Bloquear acesso").addClass("btn-outline-danger")
                $("#div_dados_config").show()
            }
            if (bloqueio === "Bloqueado") {
                label_bloqueio.text("Acesso Bloqueado").css("color", "red")
                btn_bloqueio.val("Desbloquear acesso").addClass("btn-outline-success")
                $("#div_dados_config").hide()
            }

            //Se acesso = Coordenadoria
            let acesso = "<?= $dados['acesso'] ?>"
            if (acesso == "Coordenadoria") {
                $("#div_coordenadorias").show()
            }
        })

        function bloqueioDesbloqueio() {

            let bloqueio = $("#bloqueio").val()


            if (bloqueio === "Desbloqueado") {

                let confirma = confirm("Deseja BLOQUEAR este Operador??")
                if (confirma) {
                    $("#btn_bloqueio_submit").click();
                } else {
                    return
                }

            }
            if (bloqueio === "Bloqueado") {
                let confirma = confirm("Deseja DESBLOQUEAR este Operador??")
                if (confirma) {
                    $("#btn_bloqueio_submit").click();
                } else {
                    return
                }
            }
        }
    </script>