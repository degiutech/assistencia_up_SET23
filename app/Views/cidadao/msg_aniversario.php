<div class="container">
    <h5>Enviar mensagem de aniversário</h5>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    Cidadão: <b><?= $dados['nome'] ?></b>
                </div>
                |
                <div class="col-auto">
                    Data de nascimento: <b><?= $dados['data_nasc'] ?></b>
                </div>
                |
                <div class="col-auto">
                    Idade: <b><?= $dados['idade'] ?></b>
                </div>
                |
                <div class="col-auto">
                    Celular: <b><?= $dados['celular'] ?></b>
                </div>
                |
                <div class="col-auto">
                    Email: <b><?= $dados['email'] ?></b>
                </div>
            </div>

            <hr>

            <div class="mt-3">Enviar mensagem por:</div>

            <div class="col-auto">

                <?php

                $contatos = false;

                if ($dados['email'] != '') {
                    $contatos = true;
                }

                if ($dados['whatsapp'] != '') { ?>
                    <!-- <a href="https://facebook.com/<?php $dados['whatsapp'] ?>" target="_blank"><i class="bi bi-facebook" style="font-size: 1.5rem;" title="Facebook"></i></a> -->
                    <!-- <a href="javascript:midia_social('Whatsapp: <?= $dados['whatsapp'] ?>')" class="midia_social"><i class="bi bi-whatsapp" style="font-size: 1.5rem;" title="Facebook"></i></a> -->
                    <a href="https://wa.me/55<?= $dados['whatsapp'] ?>?text=Olá, <?= $dados['nome'] ?>!" target="_blank"><i class="bi bi-whatsapp" style="margin-left: 5px; color:#128c7e; font-size: 1.5rem;"></i></a>
                <?php

                    $contatos = true;
                } ?>

                <?php if ($dados['facebook'] != '') { ?>
                    <!-- <a href="https://facebook.com/<?php $dados['facebook'] ?>" target="_blank"><i class="bi bi-facebook" style="font-size: 1.5rem;" title="Facebook"></i></a> -->
                    <a href="javascript:midia_social('Facebook: <?= $dados['facebook'] ?>')" class="midia_social"><i class="bi bi-facebook" style="font-size: 1.5rem;" title="Facebook"></i></a>

                <?php

                    $contatos = true;
                } ?>

                <?php if ($dados['instagram'] != '') { ?>
                    <!-- <a href="https://instagram.com/<?php $dados['instagram'] ?>" target="_blank"><i class="bi bi-instagram" style="font-size: 1.5rem;" title="Instagram"></i></a> -->
                    <a href="javascript:midia_social('Instagram: <?= $dados['instagram'] ?>')" class="midia_social"><i class="bi bi-instagram" style="font-size: 1.5rem;" title="Instagram"></i></a>

                <?php

                    $contatos = true;
                } ?>

                <?php if ($dados['twitter'] != '') { ?>
                    <!-- <a href="https://twitter.com/<?= $dados['twitter'] ?>" target="_blank"><i class="bi bi-twitter" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                    <a href="javascript:midia_social('Twitter: <?= $dados['twitter'] ?>')" class="midia_social"><i class="bi bi-twitter" style="font-size: 1.5rem;" title="Twitter"></i></a>

                <?php

                    $contatos = true;
                } ?>

                <?php if ($dados['telegram'] != '') { ?>
                    <!-- <a href="https://telegram.com/<?= $dados['telegram'] ?>" target="_blank"><i class="bi bi-telegram" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                    <a href="javascript:midia_social('Telegram: <?= $dados['telegram'] ?>')" class="midia_social"><i class="bi bi-telegram" style="font-size: 1.5rem;" title="Telegram"></i></a>

                <?php

                    $contatos = true;
                } ?>

                <?php if ($dados['tiktok'] != '') { ?>
                    <!-- <a href="https://tiktok.com/<?= $dados['tiktok'] ?>" target="_blank"><i class="bi bi-tiktok" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                    <a href="javascript:midia_social('Tiktok: <?= $dados['tiktok'] ?>')" class="midia_social"><i class="bi bi-tiktok" style="font-size: 1.5rem;" title="tiktok"></i></a>

                <?php

                    $contatos = true;
                }

                if (!$contatos) {
                    echo 'Não há contatos digitais registrados para <b>' . $dados['nome'] . '</b>';
                }
                ?>

            </div>

            <hr>

            <div class="mt-3">Se já finalizou o envio de mensagens para <b><?= $dados['nome'] ?></b> ou se não há como entrar em contato, retire-o(a) da lista de aniversariantes clicando no botão abaixo.</div>

            <form action="<?= $dados['url_delete_aniversariante'] ?>" method="post">

                <input type="hidden" name="id_cidadao" value="<?= $dados['id'] ?>">
                <input type="hidden" name="nome_cidadao" value="<?= $dados['nome'] ?>">

                <input type="submit" class="btn btn-outline-primary mt-2" value="Retirar da lista">

            </form>

            <hr>

            <a href="<?= $dados['url_retorno'] ?>" class="btn btn-secondary">Voltar</a>

        </div>
    </div>
</div>