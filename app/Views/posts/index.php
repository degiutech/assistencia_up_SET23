<div class="container py-5 bg-light">
    <?=Sessao::mensagem('post')?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            POSTAGENS
            <div class="float-end">
                <a href="<?=URL?>/posts/create" class="btn btn-light">Escrever</a>
            </div>
        </div>
    </div>
    <div class="card-body"></div>
    <?php foreach ($dados['posts'] as $post): ?>
    <div class="card my-5 mx-5 shadow p-3 mb-5 bg-white rounded">
        <div class="card-header bg-secondary text-white font-weight-bold border">
            <?= $post->title_post ?>
        </div>
        <div class="card-body border">

            <p class="card-text"><?=Helper::resumeText($post->text_post, 55, '<a href="#"> continue</a>')?></p>
            <a href="<?=URL.'/posts/show/'.$post->postId?>" class="btn btn-sm btn-outline-info float-end">Ler
                mais...</a>
        </div>
        <div class="card-footer text-muted border">
            <small>
                Escrito por: <?= $post->name_user ?> em <?= Check::dataBr($post->postDataRegistro) ?>
            </small>
        </div>
    </div>

    <?php endforeach?>
</div>
</div>