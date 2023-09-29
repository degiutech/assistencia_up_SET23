<div class="col-12 col-xl-8 col-md-10 mx-auto mt-5">

    <h3>Histórico de Assistência</h3>

    <div class="card">

        <div class="card-body">

            <?php if ($dados['status_atual'] == 'Finalizada') { ?>
                <div class="alert alert-primary"><b>ASSISTÊNCIA FINALIZADA</b></div>
            <?php } ?>

            <h5>Assistido(a): <span class="cor-texto" id="span_nome_cidadao_modal"><b></b></span></h5>


            <div>Descrição inicial: <b id="descricao_inicial_modal"></b>
            </div>


            <div>Status atual: <b id="status_info_modal"></b></div>
            <div>Primeiro registro: <b id="data_hora1_modal"></b></div>
            <div>Última atualização: <b id="data_hora_up_modal"></b></div>
            <div>Coordenadoria: <b id="nome_coordenadoria_modal"></b></div>

            <?php if ($dados['id_coordenadoria'] == ID_JURIDICA) { ?>
                <div>Processo Jurídico: <b id="juridica_modal"><?= $dados['assistencia']['desc_juridica'] . ' - ' . $dados['assistencia']['num_proc_juridica'] ?></b></div>
            <?php } ?>
            <?php if ($dados['id_coordenadoria'] == ID_SAUDE) { ?>
                <div>Cartão SUS: <b id="sus_historico_modal"></b></div>
            <?php } ?>

            <div class="mb-2">Primeiro registro feito por: <b id="primeiro_registro_por"></b></div>

            <hr>

            <!-- Histórico -->
            <h5 class="mt-3">Atualizações (<?= $dados['count_atualizacoes'] ?>)</h5>

            <p id="nao_contem_ups">Esta Assistência não contém atualizaões!</p>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Data e hora</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Status</th>
                        <th scope="col">Atualizada por</th>
                    </tr>
                </thead>

                <tbody id="tbody_ups">



                </tbody>
            </table>

        </div>

    </div>

</div>