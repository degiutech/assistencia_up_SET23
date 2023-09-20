<div class="container">



    <div class="row mb-1">
        <div class="col-md-6">
            <h3 class="mt-4">Assistências por Coordenadoria</h3>

            <!-- <div class="row mb-2">

                <div class="col-auto cor-texto">
                    Total geral (<?= $dados['count_geral'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Não finalizadas (<?= $dados['count_geral_nao_finalizadas'] ?>)
                </div>
                <div class="col-auto cor-texto">
                    Finalizadas (<?= $dados['count_geral_finalizadas'] ?>)
                </div>

            </div> -->

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <?= Sessao::mensagem('assistencias') ?>

            <div class="row">

                <div class="col-auto">
                    <select class="form-select" aria-label="Default select example">
                        <option value="0" selected>Selecione a Coordenadoria</option>

                        <?php if ($dados['coordenadorias'] != '') {
                            foreach ($dados['coordenadorias'] as $coord) {
                                echo '<option value="'.$coord['id'].'">'.$coord['nome'].'</option>';
                            }
                        } ?>

                    </select>
                </div>


            </div>
        </div>

    </div>