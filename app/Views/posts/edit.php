<div class="col-xl-8 col-md-8 mx-auto p-5">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=URL?>/posts">Postagens</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            Editar Post
        </div>
        <div class="card-body bg-light">

            <form method="post" name="login" action="<?=URL?>/posts/edit/<?=$dados['id']?>">

                <div class="mb-3">
                    <label for="email">Título: <sup class="text-danger">*</sup></label>
                    <input type="text" name="titulo" id="titulo" value="<?=$dados['titulo']?>"
                        class="form-control <?=$dados['titulo_erro'] != '' ? 'is-invalid' : ''?>">
                    <div class="invalid-feedback">
                        <?=$dados['titulo_erro']?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="texto">Texto: <sup class="text-danger">*</sup></label>
                    <textarea name="texto" id="texto"
                        class="form-control <?=$dados['texto_erro'] != '' ? 'is-invalid' : ''?>"
                        rows="5"><?=$dados['texto']?></textarea>
                    <div class="invalid-feedback">
                        <?=$dados['texto_erro']?>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <input type="submit" class="btn btn-info btn-block" value="Editar Postagem">
                </div>

            </form>
        </div>
    </div>
</div>