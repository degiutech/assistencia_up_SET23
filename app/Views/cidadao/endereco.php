<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <h3>Endereço</h3>

    <div class="card">

        <div class="card-body">

            <?= Sessao::mensagem('cidadao') ?>

            <form method="post" name="registrar" action="<?= URL ?>/cidadao/endereco">

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

                            <input type="text" name="uf" id="uf" value="<?= $dados['uf'] ?>" class="form-control <?= $dados['uf_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="2" placeholder="apenas 2 letras">

                            <!-- <select id="uf" class="form-select" name="uf">
                                <option value="<?= $dados['uf'] ?>" selected><?= $dados['uf'] ?></option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amap</option>
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

                    <!-- Botões -->
                    <div class="mb-3">
                        <a href="<?= URL ?>/cidadao/cancelar_cadastro_cidadao" class="btn btn-outline-warning">Cancelar</a>
                        <!-- <input type="submit" id="btn-register-endereco" class="btn btn-outline-secondary btn-block" value="Pular"> -->
                        <a href="<?= URL ?>/cidadao/create/retornar" class="btn btn-success">Voltar</a>
                        <input type="submit" id="btn-register-endereco" class="btn btn-info btn-block" value="Concluir">
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>