<div class="container my-5">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=URL?>/posts">Postagens</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$dados['post']->title_post?></li>
        </ol>
    </nav>

    <div class="card my-5 mx-5 shadow p-3 mb-5 bg-white rounded">
        <div class="card-header bg-secondary text-white font-weight-bold">
            <?=$dados['post']->title_post?>
        </div>
        <div class="card-body">
            <p class="card-text"><?=$dados['post']->text_post?></p>
        </div>

        <div class="card-footer text-muted">
            <small>
                Escrito por: <?=$dados['user']->name_user?> em <?=Check::dataBr($dados['post']->created_at)?>
            </small>
        </div>

        <?php if ($dados['post']->id_user == $_SESSION['user_id']) : ?>

        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="<?=URL.'/posts/edit/'.$dados['post']->id?>" class="btn btn-sm btn-primary">Editar</a>
            </li>
            <li class="list-inline-item mt-3">
                <form action="<?=URL.'/posts/delete/'.$dados['post']->id?>" method="POST">
                    <input type="submit" class="btn btn-sm btn-danger" value="Excluir">
                </form>
            </li>
        </ul>



        <?php endif ?>

    </div>
</div>