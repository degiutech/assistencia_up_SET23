<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <h3>Cidadão - Atualização de Endereço</h3>

    <div class="card">

        <div class="card-body">

            <form method="post" name="registrar" action="<?= URL ?>/cidadao/endereco_edit">

                <?php if (isset($dados['nome'])) { ?>
                    <h5 class="cor-texto"><?= $dados['nome'] ?></h5>
                <?php } ?>

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
                                <option value="MT" <?= ($dados['uf'] == 'MT') ? 'selected' : '' ?> <?= ($dados['uf'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                                <option value="MS" <?= ($dados['uf'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?= ($dados['uf'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                                <option value="PA" <?= ($dados['uf'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                                <option value="PB" <?= ($dados['uf'] == 'PB') ? 'selected' : '' ?>>Paraíba</option>
                                <option value="PR" <?= ($dados['uf'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                                <option value="PI" <?= ($dados['uf'] == 'PI') ? 'selected' : '' ?>>Piauí</option>
                                <option value="PE" <?= ($dados['uf'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                                <option value="RJ" <?= ($dados['uf'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                                <option value="RN" <?= ($dados['uf'] == 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                <option value="RS" <?= ($dados['uf'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                <option value="RO" <?= ($dados['uf'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                                <option value="RR" <?= ($dados['uf'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                                <option value="SC" <?= ($dados['uf'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                                <option value="SP" <?= ($dados['uf'] == 'SP') ? 'selected' : '' ?>>So Paulo</option>
                                <option value="SE" <?= ($dados['uf'] == 'SE') ? 'selected' : '' ?>>Sergipe</option>
                                <option value="TO" <?= ($dados['uf'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="mb-3">
                        <a href="<?= URL ?>/cidadao/cancelar_edicao_cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-outline-warning">Cancelar</a>
                        <input type="submit" id="btn-register-endereco" class="btn btn-info btn-block" value="Pular">
                        <input type="submit" id="btn-register-endereco" class="btn btn-info btn-block" value="Continuar">
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>