<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <div class="row mb-1">
        <div class="col-md-6">
            <h3>Cidadão - Atualização de Cadastro</h3>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="margin-right: 10px;">HOME</a>
            <?php } ?>
            <a href="<?= URL ?>/cidadao/cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-info">Info Cidadão</a>
        </div>
    </div>

    <div class="card">

        <div class="card-body">

            <?= Sessao::mensagem('cidadao') ?>

            <!-- <p class="card-text"><small>Preencha o formulário para fazer seu registro.</small></p> -->

            <form method="post" name="registrar" action="<?= URL ?>/cidadao/edit/<?= $dados['id_cidadao'] ?>">

                <p>Informações iniciais</p>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="nome">Nome <sup class="text-danger">*</sup></label>
                        <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" maxlength="40" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['nome_erro'] ?>
                        </div>
                    </div>

                    <!-- Sexo -->
                    <div class="col-md-6 mb-3">
                        <label for="nome">Sexo</label>

                        <div class="form-control">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="inlineRadio1" value="Masc." <?= ($dados['sexo'] == 'Masc.') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">Masc.</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="inlineRadio2" value="Fem." <?= ($dados['sexo'] == 'Fem.') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">Fem.</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="inlineRadio3" value="Não declarado" <?= ($dados['sexo'] == 'Não declarado') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio3">Não declarado</label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-5 mb-3">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" value="<?= $dados['email'] ?>" maxlength="50" class="form-control <?= $dados['email_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['email_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="celular">Celular</label>
                        <input type="tel" name="celular" id="celular" value="<?= $dados['celular'] ?>" maxlength="50" class="celular form-control <?= $dados['celular_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['celular_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="celular">Data de Nascimento</label>
                        <input type="date" name="data_nasc" id="data_nasc" value="<?= $dados['data_nasc'] ?>" class="form-control <?= $dados['data_nasc_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['data_nasc_erro'] ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 col-sm-3 mb-3">
                        <label for="cpf">CPF <sup class="text-danger">*</sup></label>
                        <input type="text" name="cpf" id="cpf" value="<?= $dados['cpf'] ?>" class="cpf form-control <?= $dados['cpf_erro'] != '' ? 'is-invalid' : '' ?>" data-mask="000.000.000-00" maxlength="14">
                        <div class="invalid-feedback" id="cep_erro">
                            <?= $dados['cpf_erro'] ?>
                        </div>
                        <div>
                            <small id="cpf_feedback" style="color: red"></small>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-7 mb-3">
                        <label for="rg">Identidade </label>
                        <input type="text" name="rg" id="rg" value="<?= $dados['rg'] ?>" class="form-control <?= $dados['rg_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="18" placeholder="somente números">
                        <div class="invalid-feedback">
                            <?= $dados['rg_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-2 mb-3">
                        <label for="orgao_expedidor">Orgão Expedidor </label>
                        <input type="text" name="orgao_expedidor" id="orgao_expedidor" value="<?= $dados['orgao_expedidor'] ?>" maxlength="12" class="form-control <?= $dados['orgao_expedidor_erro'] != '' ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback">
                            <?= $dados['orgao_expedidor_erro'] ?>
                        </div>
                    </div>

                </div>

                <!-- Título Eleitor e SUS -->
                <div class="row">

                    <div class="col-md-3 col-sm-3 mb-3">
                        <label for="titulo">Título de Eleitor </label>
                        <input type="text" name="titulo" id="titulo" value="<?= $dados['titulo'] ?>" maxlength="20" class="form-control <?= $dados['titulo_erro'] != '' ? 'is-invalid' : '' ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        <div class="invalid-feedback">
                            <?= $dados['titulo_erro'] ?>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 mb-3">
                        <label for="bairro">Zona </label>
                        <input type="text" name="zona" id="zona" value="<?= $dados['zona'] ?>" class="form-control <?= $dados['zona_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="5" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        <div class="invalid-feedback">
                            <?= $dados['zona_erro'] ?>
                        </div>
                    </div>

                    <div class="col mb-3">
                        <label for="secao">Seção </label>
                        <input type="text" name="secao" id="secao" value="<?= $dados['secao'] ?>" class="form-control <?= $dados['secao_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="5" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        <div class="invalid-feedback">
                            <?= $dados['secao_erro'] ?>
                        </div>
                    </div>

                    <!-- SUS -->
                    <div class="col mb-3">
                        <label for="sus">Cartão SUS </label>
                        <input type="text" name="sus" id="sus" value="<?= $dados['sus'] ?>" class="sus form-control <?= $dados['sus_erro'] != '' ? 'is-invalid' : '' ?>" maxlength="18" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="somente números">
                        <div class="invalid-feedback">
                            <?= $dados['sus_erro'] ?>
                        </div>
                    </div>

                </div>

                <!-- Botões -->
                <div class="mb-3">
                    <a href="<?= URL ?>/cidadao/cancelar_edicao_cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-outline-warning">Cancelar</a>
                    <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Continuar">
                </div>

                <sup class="text-danger">*</sup> <small>Obrigatórios</small>

            </form>
        </div>
    </div>
</div>