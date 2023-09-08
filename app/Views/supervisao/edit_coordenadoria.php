<?= Sessao::mensagem('coord') ?>

<div class="col-12 col-xl-6 col-md-10 mx-auto mt-5">

    <h3>Editar Coordenadoria</h3>

    <div class="card">

        <div class="card-body">

            <form action="<?= URL ?>/supervisao/edit_coordenadoria/<?= $dados['id'] ?>" method="post">

            <input type="hidden" value="<?= $dados['id'] ?>" name="id">

                <label class="form-label" for="nome">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?= $dados['nome'] ?>">

                <input type="submit" class="btn btn-info mt-3" value="Enviar">

            </form>

        </div>
    </div>
</div>