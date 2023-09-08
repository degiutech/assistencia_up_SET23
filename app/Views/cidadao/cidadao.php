<?= Sessao::mensagem('cidadao') ?>
<?= Sessao::mensagem('assistencia') ?>

<div class="container mt-5">

    <h3>Informações de Cidadão</h3>

    <div class="row mb-2">
        <div class="col-md-6">
            <h4 class="cor-texto"><?= $dados['cidadao']['nome'] ?></h4>
        </div>
        <div class="col-md-6 d-flex justify-content-end ">
            <?php if (isset($dados['home'])) { ?>
                <a href="<?= $dados['home'] ?>" class="btn btn-secondary btn-block" style="margin-right: 10px;">HOME</a>
            <?php } ?>
            <a href="<?= URL ?>/diversos/create_assistencia/<?= $dados['cidadao']['id'] ?>" class="btn btn-success btn-block">Nova Assistência</a>
            <a href="<?= URL ?>/cidadao/edit/<?= $dados['cidadao']['id'] ?>" class="btn btn-info btn-block" style="margin-left: 10px;">Atualizar Cadastro</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div>INFORMAÇÕES PESSOAIS</div>

            <hr>

            <table class="table">
                <tbody>
                    <tr>
                        <td>Celular</td>
                        <th><?= $dados['cidadao']['celular'] ?>

                            <?php if (isset($dados['whatsapp']) && !empty($dados['whatsapp'])) { ?>

                                <a href="https://wa.me/55<?= $dados['whatsapp'] ?>?text=Olá, <?= $dados['cidadao']['nome'] ?>!" target="_blank"><i class="bi bi-whatsapp" style="margin-left: 5px; color:#128c7e;"></i></a>

                            <?php } ?>
                        </th>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <th><?= $dados['cidadao']['email'] ?></th>
                    </tr>

                    <!-- Endereço -->
                    <?php if ($dados['cidadao']['logradouro']) { ?>
                        <tr>
                            <td>Endereço</td>
                            <th><?= $dados['cidadao']['logradouro'] . ', ' . $dados['cidadao']['numero'] . ', ' . $dados['cidadao']['complemento'] ?></th>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td>Endereço</td>
                            <th>Endereço não registrado</th>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>Bairro</td>
                        <th><?= $dados['cidadao']['bairro'] ?></th>
                    </tr>
                    <tr>
                        <td>Cidade</td>
                        <th><?= $dados['cidadao']['cidade'] . ' - ' . $dados['cidadao']['uf'] ?></th>
                    </tr>
                    <tr>
                        <td>Idade</td>
                        <th><?= $dados['idade'] ?></th>
                    </tr>
                    <tr>
                        <td>CPF</td>
                        <th><?= $dados['cpf'] ?></th>
                    </tr>
                    <tr>
                        <td>Cartão SUS</td>
                        <th><?= $dados['sus'] ?></th>
                    </tr>
                </tbody>
            </table>

            <!-- midias sociais -->
            <div class="row mb-3">


                <div class="col-auto">
                    <?php if ($dados['cidadao']['facebook'] != '') { ?>
                        <!-- <a href="https://facebook.com/<?php $dados['cidadao']['facebook'] ?>" target="_blank"><i class="bi bi-facebook" style="font-size: 1.5rem;" title="Facebook"></i></a> -->
                        <a href="javascript:midia_social('Facebook: <?= $dados['cidadao']['facebook'] ?>')" class="midia_social"><i class="bi bi-facebook" style="font-size: 1.5rem;" title="Facebook"></i></a>

                    <?php } ?>

                    <?php if ($dados['cidadao']['instagram'] != '') { ?>
                        <!-- <a href="https://instagram.com/<?php $dados['cidadao']['instagram'] ?>" target="_blank"><i class="bi bi-instagram" style="font-size: 1.5rem;" title="Instagram"></i></a> -->
                        <a href="javascript:midia_social('Instagram: <?= $dados['cidadao']['instagram'] ?>')" class="midia_social"><i class="bi bi-instagram" style="font-size: 1.5rem;" title="Instagram"></i></a>

                    <?php } ?>

                    <?php if ($dados['cidadao']['twitter'] != '') { ?>
                        <!-- <a href="https://twitter.com/<?= $dados['cidadao']['twitter'] ?>" target="_blank"><i class="bi bi-twitter" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                        <a href="javascript:midia_social('Twitter: <?= $dados['cidadao']['twitter'] ?>')" class="midia_social"><i class="bi bi-twitter" style="font-size: 1.5rem;" title="Twitter"></i></a>

                    <?php } ?>

                    <?php if ($dados['cidadao']['telegram'] != '') { ?>
                        <!-- <a href="https://telegram.com/<?= $dados['cidadao']['telegram'] ?>" target="_blank"><i class="bi bi-telegram" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                        <a href="javascript:midia_social('Telegram: <?= $dados['cidadao']['telegram'] ?>')" class="midia_social"><i class="bi bi-telegram" style="font-size: 1.5rem;" title="Telegram"></i></a>

                    <?php } ?>

                    <?php if ($dados['cidadao']['tiktok'] != '') { ?>
                        <!-- <a href="https://tiktok.com/<?= $dados['cidadao']['tiktok'] ?>" target="_blank"><i class="bi bi-tiktok" style="font-size: 1.5rem;" title="Twitter"></i></a> -->
                        <a href="javascript:midia_social('Tiktok: <?= $dados['cidadao']['tiktok'] ?>')" class="midia_social"><i class="bi bi-tiktok" style="font-size: 1.5rem;" title="tiktok"></i></a>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-3 mb-2">
        <div class="card-body">

            <div>ASSISTÊNCIAS</div>

            <hr>


        </div>
    </div>

    <!-- Assistências -->
    <div class="accordion accordion-flush" id="accordionFlushExample">

        <?php if ($dados['assistencias']) {

            foreach ($dados['assistencias'] as $ass) {

                $cor = '';

                if ($ass['status_atual'] == 'Em andamento' || $ass['status_atual'] == 'Aguardando') {
                    $cor = 'style="color:#FE642E"';
                }
                if ($ass['status_atual'] == 'Finalizada') {
                    $cor = 'style="color:#688A08"';
                }

        ?>

                <div class="card mb-2">
                    <div class="card-bory">


                        <div class="accordion-item">
                            <h2 class="accordion-header" id="ass_flush<?= $ass['id'] ?>">
                                <button class="accordion-button collapsed btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#ass_colapse<?= $ass['id'] ?>" aria-expanded="false" aria-controls="ass_colapse<?= $ass['id'] ?>">

                                    <h6 style="color: #8B4513;"> <?= $ass['data'] . ' - ' . $ass['descricao'] . $ass['complemento'] ?>

                                        <code style="color: #4F4F4F;">
                                            <h6>Coordenadoria - <?= $ass['nome_coordenadoria'] ?></h6>
                                        </code>

                                        <!-- Processo jurídico -->
                                        <?php if ($ass['id_coordenadoria'] == 3) { ?>
                                            <code style="color: #4F4F4F;">
                                                <div><?= $ass['desc_juridica'] . ' - ' . $ass['num_proc_juridica'] ?></div>
                                            </code>
                                        <?php } ?>

                                        <!-- <?= ($ass['status_atual'] == 'Inalterado') ? '' : 'style="color:green""' ?> -->
                                        <code <?= $cor ?>>
                                            <h6><?= $ass['status_atual'] ?></h6>
                                        </code>

                                    </h6>

                                </button>

                            </h2>
                            <div id="ass_colapse<?= $ass['id'] ?>" class="accordion-collapse collapse" aria-labelledby="ass_flush<?= $ass['id'] ?>" data-bs-parent="#accordionFlushExample" style="background-color:#F0F8FF">
                                <div class="accordion-body border">

                                    <!-- Botões -->
                                    <div class="mb-3">

                                        <?php if ($ass['status_atual'] != 'Finalizada') {
                                            $status_atual = $ass['status_atual'];
                                            if ($status_atual == 'Sem atualizações') {
                                                $status_atual = 'Sem_atualizacoes';
                                            }
                                            if ($status_atual == 'Em andamento') {
                                                $status_atual = 'Em_andamento';
                                            }
                                            ?>
                                            <a href="<?= URL ?>/assistencias/finalizar/<?= $ass['id'] ?>" class="btn btn-outline-success btn-sm">Finalizar</a>
                                            <a href="<?= URL ?>/assistencias/update_status/<?= $ass['id'] ?>/<?= $status_atual ?>" class="btn btn-outline-secondary btn-sm">Atualizar</a>
                                        <?php } ?>

                                    </div>

                                    <hr>

                                    <?php foreach ($ass['updates'] as $up) {

                                        $up_alterada = 'Alterada em';

                                        if ($up['status_updated'] == 'Finalizada') {
                                            $up_alterada = 'Finalizada em';
                                        }
                                        if ($up['status_updated'] == 'Iniciada') {
                                            $up_alterada = 'Registrada em';
                                        }

                                    ?>

                                        <div class="row">

                                            <div class="col-md-2">
                                                <div class="car-title"><?= $up_alterada ?> </div>
                                                <p><?= $up['data'] ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="car-title">Descrição </div>
                                                <p><?= $up['status_compl_updated'] ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="car-title">Operador </div>
                                                <p><?= $up['name_updated_by'] ?></p>
                                            </div>

                                        </div>

                                        <hr>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            <?php }
        } else { ?>
            <div class="card">
                <div class="card-body">
                    <?= 'Não há registros de Assistências para <b>' . $dados['cidadao']['nome'] . '</b>' ?>
                </div>
            </div>

        <?php }
        ?>

    </div>



</div>

<script>
    $(document).ready(() => {
        // $(".midia_social").on('click', function () {
        //     alert($(".midia_social").attr("id"));
        // })

    })

    function opcao_finalizar_ass(id) {
        // $("#btn_modal_finalizar").click()
        alert(id)
    }

    function midia_social(midia) {
        alert(midia);
    }

    //modal assistencia
    function click_modal(id) {
        alert(id)
        // $("#btn_modal").click()
    }

    //cor de status atual
    function valor_status_atual(id) {
        alert($(".status_atual").text());
    }
</script>