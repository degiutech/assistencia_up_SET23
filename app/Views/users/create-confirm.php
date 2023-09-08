<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5" style="margin-bottom: 250px;">

    <div class="card">

        <div class="card-header bg-secondary text-white">
            <h3>Está tudo certo?</h3>
        </div>

        <div class="card">

            <div class="card-body">

                <form method="POST" name="registrar" action="<?= URL ?>/users/register">

                    <div class="form-row">
                        <label for="nome"><b>Nome: </b></label>
                        <input type="text" name="nome" id="nome" value="<?= $dados['nome'] ?>" style="border: none"
                            readonly>
                    </div>

                    <div class="form-row">
                        <label for="email"><b>E-mail: </b></label>
                        <input type="text" name="email" id="email" value="<?= $dados['email'] ?>" style="border: none"
                            readonly>
                    </div>

                    <div class="form-row">
                        <label for="cep"><b>Cep: </b></label>
                        <input type="text" name="cep" id="cep" value="<?= $dados['cep'] ?>" style="border: none"
                            readonly>
                    </div>
                    <div class="form-row">
                        <label for="logradouro"><b>Logradouro: </b></label>
                        <input type="text" name="logradouro" id="logradouro" value="<?= $dados['logradouro'] ?>"
                            style="border: none" readonly>
                    </div>

                    <div class="form-row">
                        <label for="numero"><b>Nº: </b></label>
                        <input type="text" name="numero" id="numero" value="<?= $dados['numero'] ?>"
                            style="border: none" readonly>
                    </div>

                    <div class="form-row">
                        <label for="complemento"><b>Complemento: </b></label>
                        <input type="text" name="complemento" id="complemento" value="<?= $dados['complemento'] ?>"
                            style="border: none" readonly>
                    </div>

                    <div class="form-row">
                        <label for="bairro"><b>Bairro: </b></label>
                        <input type="text" name="bairro" id="bairro" value="<?= $dados['bairro'] ?>"
                            style="border: none" readonly>
                    </div>

                    <div class="form-row">
                        <label for="cidade"><b>Cidade: </b></label>
                        <input type="text" name="cidade" id="cidade" value="<?= $dados['cidade'] ?>"
                            style="border: none" readonly>
                    </div>

                    <div class="form-row">
                        <label for="estado"><b>Estado: </b></label>
                        <input type="text" name="uf" id="uf" value="<?= $dados['uf'] ?>" style="border: none" readonly>
                    </div>

                    <div class="form-row mt-3">
                        <a href="<?=URL?>/users/returnFormCreate" class="btn btn-outline-info">Retornar</a>
                        <button type="submit" class="btn btn-info">Continuar</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>