<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">

    <h3>Endereço e Local de Trabalho de Operador do Sistema</h3>

    <div class="card">

        <div class="card-body">

            <form method="post" name="registrar" action="<?= URL ?>/users/update_endereco">

                <input name="ibge" type="hidden" id="ibge" size="8" />

                <div class="mb-3">
                    <h5 class="cor-texto"><span>Nome: </span><b><?= $dados['nome'] ?></b></h5>
                </div>

                Endereço

                <div class="card mb-3">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">
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

                                    <input type="text" name="uf" id="uf" value="<?= $dados['uf'] ?>" class="form-control <?= $dados['uf_erro'] != '' ? 'is-invalid' : '' ?>">

                                    <!-- <select id="uf" class="form-select" name="uf">
                                <option value="<?= $dados['uf'] ?>" selected><?= $dados['uf'] ?></option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                Local de Trabalho
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-5 mb-3">
                                <label for="local_trabalho">Local: <sup class="text-danger">*</sup></label>
                                <input type="text" name="local_trabalho" id="local_trabalho" value="<?= $dados['local_trabalho'] ?>" maxlength="50" class="form-control <?= $dados['local_trabalho_erro'] != '' ? 'is-invalid' : '' ?>" required>
                                <div class="invalid-feedback">
                                    <?= $dados['local_trabalho_erro'] ?>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="cidade_trabalho">Cidade:</label>
                                <input type="text" name="cidade_trabalho" id="cidade_trabalho" value="<?= $dados['cidade_trabalho'] ?>" maxlength="50" class="form-control <?= $dados['cidade_trabalho_erro'] != '' ? 'is-invalid' : '' ?>" required>
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

                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="mb-3 mt-3">
                    <div class="row">
                        <div class="col-md-6 p-3">
                            <a href="<?= URL ?>/users/create" class="btn btn-outline-warning">Cancelar</a>
                            <!-- <a href="javascript:pular()" class="btn btn-outline-secondary">Pular</a> -->
                            <input type="submit" id="btn-register-endereco" class="btn btn-info btn-block" value="Continuar">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>