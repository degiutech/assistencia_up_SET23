<?= Sessao::mensagem('coord') ?>

<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">



    <div class="row mb-1">
        <div class="col-md-6">
            <div class="col-md-6">
                <h3>Nova Coordenadoria</h3>
            </div>

        </div>

        <div class="col-md-6 d-flex justify-content-end align-items-end">

            <a href="<?= URL ?>/operadores" class="btn btn-outline-secondary">Operadores</a>

            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="float:right; margin-right: 5px">HOME</a>
            <?php } ?>
        </div>
    </div>

    <div class="card">

        <div class="card-body">

            <form action="<?= URL ?>/supervisao/create_coordenadoria" method="post">

                <label class="form-label" for="nome">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>" value="<?= $dados['nome'] ?>">

                <div class="invalid-feedback">
                    <?= $dados['nome_erro'] ?>
                </div>

                <input type="submit" class="btn btn-info mt-3" value="Enviar">

            </form>

        </div>
    </div>
</div>