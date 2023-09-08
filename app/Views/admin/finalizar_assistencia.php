
<div class="container col-md-8">

    <div class="row">

        <div class="col-md-6">
            <h3>Finalizar Assistência</h3>
        </div>

        <div class="col-md-6 d-md-flex justify-content-end">
            <div>
                <a href="<?= URL ?>/cidadao/cidadao/<?= $dados['id_cidadao'] ?>" class="btn btn-secondary">Cidadão</a>
            </div>
        </div>

    </div>


    <div class="card">
        <div class="card-body">
            <?php Sessao::mensagem('assistencia') ?>


            <form method="post" name="registrar" action="<?= URL ?>/admin/finalizar_assistencia/<?= $dados['id_assistencia'] ?>">

                <input type="hidden" name="id_cidadao" value="<?= $dados['id_cidadao'] ?>">
                <input type="hidden" name="id_assistencia" value="<?= $dados['id_assistencia'] ?>">

                <!--<input type="hidden" name="id_created_by" value="<?= $dados['id_created_by'] ?>">
                <input type="hidden" name="name_created_by" value="<?= $dados['name_created_by'] ?>"> -->


                <h5 class="cor-texto"><span>Nome do Cidadão: <b><?= $dados['nome_cidadao'] ?></b></span></h5>
                <h6>Assistência: <span><?= $dados['descricao'] ?></span></h6>

                <hr>


                <!-- Breve descrição do status -->
                <div class="col-md-6">

                    <label for="status_complemento" class="form-label">Ações Realizadas</label>

                    <button type="button" class="btn btn-light bg-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Ex. Conseguimos os remédios solicitados.">
                        <i class="bi bi-question-circle col-auto"></i>
                    </button>

                    <textarea name="status_complemento" id="status_complemento" class="form-control" cols="20" rows="3" maxlength="100" placeholder="máx. 100 caracteres"></textarea>

                </div>

                <!-- Botões -->
                <div class="mb-3 mt-4">

                    <p style="color: orange">Deseja mesmo finalizar esta Assistência?</p>

                    <a href="<?= $dados['home'] ?>" class="btn btn-outline-warning">Cancelar</a>
                    <input type="submit" id="btn-register" class="btn btn-info btn-block" value="Finalizar Assistência">
                </div>

                <sup class="text-danger">*</sup> <small>Obrigatórios</small>

            </form>

        </div>

    </div>

    <script>
        $("#status_complemento").val('<?= $dados['status_complemento'] ?>')
    </script>