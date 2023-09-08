<div class="container">

    <div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

        <div class="row mb-3">
            <div class="col-md-8 col-xl-8 d-flex justify-content-start">
                <h3>Editar Operador</h3>
            </div>
            <div class="col-md-4 col-xl-4 d-flex justify-content-end">

                <a href="<?= URL ?>/operadores/update_pass_op/<?= $dados['id'] ?>" class="btn btn-secondary" style="margin-right: 5px;">Trocar Senha</a>

                <a href="javascript:window.history.back()" class="btn btn-secondary">Voltar</a>

            </div>
        </div>

        <div class="card">

            <div class="card-body">

                <div class="container">

                    <?= Sessao::mensagem('user'); ?>

                    <form method="post" name="registrar" action="<?= URL ?>/operadores/edit_operador">

                        <input type="hidden" name="bloqueio" id="bloqueio" value="<?= $dados['bloqueio'] ?>">

                        <? $dados['msg']; ?>

                        <div id="div_bloqueio" style="display: none;">
                            <h5><?= $dados['nome'] ?></h5>
                            <p style="color:red;">ACESSO BLOQUEADO
                                <span><a href="<?= URL ?>/users/config_operador/<?= $dados['id'] ?>" class="btn btn-outline-danger btn-sm">Desbloquear</a></span>
                            </p>
                        </div>

                        <div id="div_desbloqueio">

                            <h5 class="azul-cinza mt-3">Informações essenciais</h5>

                            <input type="hidden" value="<?= $dados['id'] ?>" name="id">

                            <div class="mb-3">
                                <label for="nome">Nome: <sup class="text-danger">*</sup></label>
                                <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" maxlength="40" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>" readonly>
                                <div class="invalid-feedback">
                                    <?= $dados['nome_erro'] ?>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-7 mb-3">
                                    <label for="email">E-mail: <sup class="text-danger">*</sup></label>
                                    <input type="email" name="email" id="email" value="<?= $dados['email'] ?>" maxlength="50" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                                    <div class="invalid-feedback">
                                        <?= $dados['email_erro'] ?>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label for="celular">Celular:</label>
                                    <input type="tel" name="celular" id="celular" value="<?= $dados['celular'] ?>" maxlength="50" class="celular form-control <?= $dados['celular_erro'] != '' ? 'is-invalid' : '' ?>">
                                    <div class="invalid-feedback">
                                        <?= $dados['celular_erro'] ?>
                                    </div>
                                </div>

                            </div>

                            <!-- Endereço -->
                            <!-- <input type="hidden" name="id" value="<?= $dados['id'] ?>"> -->
                            <h5 class="azul-cinza mt-3">Endereço residencial</h5>

                            <input name="ibge" type="hidden" id="ibge" size="8" />

                            <div class="row">

                                <div class="col-md-3 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="cep">CEP:</label>
                                        <input type="text" name="cep" id="cep" value="<?= $dados['cep'] ?>" class="cep form-control <?= $dados['cep_erro'] != '' ? 'is-invalid' : '' ?>" onkeyup="enderecoViaCep();" onblur="enderecoViaCep();" data-mask="00000-000">
                                        <div class="invalid-feedback" id="cep_erro">
                                            <?= $dados['cep_erro'] ?>
                                        </div>
                                        <div>
                                            <small id="cep_feedback" style="color: red"></small>
                                        </div>
                                        <a href='https://buscacepinter.correios.com.br/app/endereco/index.php?t' target='blank'>consulte seu cep aqui</a>
                                    </div>
                                </div>

                                <div class="col-md-7 col-sm-7 mb-3">
                                    <div class="mb-3">
                                        <label for="logradouro">Logradouro: </label>
                                        <input type="text" name="logradouro" id="logradouro" value="<?= $dados['logradouro'] ?>" class="form-control <?= $dados['logradouro_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['logradouro_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-2 mb-3">
                                    <div class="mb-3">
                                        <label for="numero">Nº: </label>
                                        <input type="text" name="numero" id="numero" value="<?= $dados['numero'] ?>" maxlength="12" class="form-control <?= $dados['numero_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['numero_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="complemento">Complemento: </label>
                                        <input type="text" name="complemento" id="complemento" value="<?= $dados['complemento'] ?>" maxlength="20" class="form-control <?= $dados['complemento_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['complemento_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="bairro">Bairro: </label>
                                        <input type="text" name="bairro" id="bairro" value="<?= $dados['bairro'] ?>" class="form-control <?= $dados['bairro_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['bairro_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col mb-3">
                                    <div class="mb-3">
                                        <label for="cidade">Cidade: </label>
                                        <input type="text" name="cidade" id="cidade" value="<?= $dados['cidade'] ?>" class="form-control <?= $dados['cidade_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['cidade_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col mb-3">
                                    <div class="mb-3">
                                        <label for="uf">Estado</label>

                                        <select id="uf" class="form-select" name="uf">
                                            <option value="" <?= ($dados['uf'] == '') ? 'selected' : '' ?>>Selecione</option>
                                            <option value="AC" <?= ($dados['uf'] == 'AC') ? 'selected' : '' ?>>Acre</option>
                                            <option value="AL" <?= ($dados['uf'] == 'AL') ? 'selected' : '' ?>>Alagoas</option>
                                            <option value="AP" <?= ($dados['uf'] == 'AP') ? 'selected' : '' ?>>Amapá</option>
                                            <option value="AM" <?= ($dados['uf'] == 'AM') ? 'selected' : '' ?>>Amazonas</option>
                                            <option value="BA" <?= ($dados['uf'] == 'BA') ? 'selected' : '' ?>>Bahia</option>
                                            <option value="CE" <?= ($dados['uf'] == 'CE') ? 'selected' : '' ?>>Ceará</option>
                                            <option value="DF" <?= ($dados['uf'] == 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                                            <option value="ES" <?= ($dados['uf'] == 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                                            <option value="GO" <?= ($dados['uf'] == 'GO') ? 'selected' : '' ?>>Goiás</option>
                                            <option value="MA" <?= ($dados['uf'] == 'MA') ? 'selected' : '' ?>>Maranhão</option>
                                            <option value="MT" <?= ($dados['uf'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                                            <option value="MS" <?= ($dados['uf'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                            <option value="MG" <?= ($dados['uf'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                                            <option value="PA" <?= ($dados['uf'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                                            <option value="PB" <?= ($dados['uf'] == 'PB') ? 'selected' : '' ?>>Paraíba</option>
                                            <option value="PR" <?= ($dados['uf'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                                            <option value="PE" <?= ($dados['uf'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                                            <option value="PI" <?= ($dados['uf'] == 'PI') ? 'selected' : '' ?>>Piauí</option>
                                            <option value="RJ" <?= ($dados['uf'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                                            <option value="RN" <?= ($dados['uf'] == 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                            <option value="RS" <?= ($dados['uf'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                            <option value="RO" <?= ($dados['uf'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                                            <option value="RR" <?= ($dados['uf'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                                            <option value="SC" <?= ($dados['uf'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                                            <option value="SP" <?= ($dados['uf'] == 'SP') ? 'selected' : '' ?>>São Paulo</option>
                                            <option value="SE" <?= ($dados['uf'] == 'SE') ? 'selected' : '' ?>>Sergipe</option>
                                            <option value="TO" <?= ($dados['uf'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <!-- Documentos pessoais -->

                            <h5 class="azul-cinza mt-3">Documentos pessoais</h5>

                            <input name="ibge" type="hidden" id="ibge" size="8" />

                            <div class="row">

                                <div class="col-md-4 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="cpf">CPF:</label>
                                        <input type="text" name="cpf" id="cpf" value="<?= $dados['cpf'] ?>" class="cpf form-control <?= $dados['cpf_erro'] != '' ? 'is-invalid' : '' ?>" data-mask="000.000.000-00" maxlength="14">
                                        <div class="invalid-feedback" id="cep_erro">
                                            <?= $dados['cpf_erro'] ?>
                                        </div>
                                        <div>
                                            <small id="cpf_feedback" style="color: red"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-7 mb-3">
                                    <div class="mb-3">
                                        <label for="rg">Identidade: </label>
                                        <input type="text" name="rg" id="rg" value="<?= $dados['rg'] ?>" class="form-control <?= $dados['rg_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="18" placeholder="somente números">
                                        <div class="invalid-feedback">
                                            <?= $dados['rg_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-2 mb-3">
                                    <div class="mb-3">
                                        <label for="orgao_expedidor">Orgão Expedidor: </label>
                                        <input type="text" name="orgao_expedidor" id="orgao_expedidor" value="<?= $dados['orgao_expedidor'] ?>" maxlength="12" class="form-control <?= $dados['orgao_expedidor_erro'] != '' ? 'is-invalid' : '' ?>">
                                        <div class="invalid-feedback">
                                            <?= $dados['orgao_expedidor_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="titulo">Título de Eleitor: </label>
                                        <input type="text" name="titulo" id="titulo" value="<?= $dados['titulo_eleitor'] ?>" maxlength="20" class="form-control <?= $dados['titulo_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        <div class="invalid-feedback">
                                            <?= $dados['titulo_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-3 mb-3">
                                    <div class="mb-3">
                                        <label for="bairro">Zona: </label>
                                        <input type="text" name="zona" id="zona" value="<?= $dados['zona_eleitoral'] ?>" class="form-control <?= $dados['zona_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        <div class="invalid-feedback">
                                            <?= $dados['zona_erro'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col mb-3">
                                    <div class="mb-3">
                                        <label for="secao">Seção: </label>
                                        <input type="text" name="secao" id="secao" value="<?= $dados['secao_eleitoral'] ?>" class="form-control <?= $dados['secao_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        <div class="invalid-feedback">
                                            <?= $dados['secao_erro'] ?>
                                        </div>
                                    </div>
                                </div>


                            </div>



                            <h5 class="azul-cinza mt-3">Local de trabalho</h5>

                            <div class="row">

                                <div class="col-md-5 mb-3">
                                    <label for="local_trabalho">Local: <sup class="text-danger">*</sup></label>
                                    <input type="text" name="local_trabalho" id="local_trabalho" value="<?= $dados['local_trabalho'] ?>" maxlength="50" class="form-control <?= $dados['local_trabalho_erro'] != '' ? 'is-invalid' : '' ?>">
                                    <div class="invalid-feedback">
                                        <?= $dados['local_trabalho_erro'] ?>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="cidade_trabalho">Cidade:</label>
                                    <input type="text" name="cidade_trabalho" id="cidade_trabalho" value="<?= $dados['cidade_trabalho'] ?>" maxlength="50" class="form-control <?= $dados['cidade_trabalho_erro'] != '' ? 'is-invalid' : '' ?>">
                                    <div class="invalid-feedback">
                                        <?= $dados['cidade_trabalho_erro'] ?>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="mb-3">
                                        <label for="uf_trabalho">Estado</label>
                                        <select id="uf_trabalho" class="form-select" name="uf_trabalho">
                                            <option value="" <?= ($dados['uf_trabalho'] == '') ? 'selected' : '' ?>>UF do trabalho</option>
                                            <option value="AC" <?= ($dados['uf_trabalho'] == 'AC') ? 'selected' : '' ?>>Acre</option>
                                            <option value="AL" <?= ($dados['uf_trabalho'] == 'AL') ? 'selected' : '' ?>>Alagoas</option>
                                            <option value="AP" <?= ($dados['uf_trabalho'] == 'AP') ? 'selected' : '' ?>>Amapá</option>
                                            <option value="AM" <?= ($dados['uf_trabalho'] == 'AM') ? 'selected' : '' ?>>Amazonas</option>
                                            <option value="BA" <?= ($dados['uf_trabalho'] == 'BA') ? 'selected' : '' ?>>Bahia</option>
                                            <option value="CE" <?= ($dados['uf_trabalho'] == 'CE') ? 'selected' : '' ?>>Ceará</option>
                                            <option value="DF" <?= ($dados['uf_trabalho'] == 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                                            <option value="ES" <?= ($dados['uf_trabalho'] == 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                                            <option value="GO" <?= ($dados['uf_trabalho'] == 'GO') ? 'selected' : '' ?>>Goiás</option>
                                            <option value="MA" <?= ($dados['uf_trabalho'] == 'MA') ? 'selected' : '' ?>>Maranhão</option>
                                            <option value="MT" <?= ($dados['uf_trabalho'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                                            <option value="MS" <?= ($dados['uf_trabalho'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                            <option value="MG" <?= ($dados['uf_trabalho'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                                            <option value="PA" <?= ($dados['uf_trabalho'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                                            <option value="PB" <?= ($dados['uf_trabalho'] == 'PB') ? 'selected' : '' ?>>Paraíba</option>
                                            <option value="PR" <?= ($dados['uf_trabalho'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                                            <option value="PE" <?= ($dados['uf_trabalho'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                                            <option value="PI" <?= ($dados['uf_trabalho'] == 'PI') ? 'selected' : '' ?>>Piauí</option>
                                            <option value="RJ" <?= ($dados['uf_trabalho'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                                            <option value="RN" <?= ($dados['uf_trabalho'] == 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                            <option value="RS" <?= ($dados['uf_trabalho'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                            <option value="RO" <?= ($dados['uf_trabalho'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                                            <option value="RR" <?= ($dados['uf_trabalho'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                                            <option value="SC" <?= ($dados['uf_trabalho'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                                            <option value="SP" <?= ($dados['uf_trabalho'] == 'SP') ? 'selected' : '' ?>>São Paulo</option>
                                            <option value="SE" <?= ($dados['uf_trabalho'] == 'SE') ? 'selected' : '' ?>>Sergipe</option>
                                            <option value="TO" <?= ($dados['uf_trabalho'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                                        </select>
                                    </div>

                                </div>



                                <!-- Botões -->
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="javascript:window.history.back()" class="btn btn-outline-info">Cancelar</a>
                                            <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Enviar">
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <a href="<?= URL ?>/users/login">Já é registrado? Faça o login</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        let bloqueio = $("#bloqueio").val()

        if (bloqueio === "Bloqueado") {
            $("#div_bloqueio").show()
            $("#div_desbloqueio").hide()
        } else {
            $("#div_bloqueio").hide()
            $("#div_desbloqueio").show()
        }
    })
</script>