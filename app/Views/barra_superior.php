<?php 
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    if ($user['nome_coordenadoria']) {
        $nome_coord = ' de ' . $user['nome_coordenadoria'];
    } else {
        $nome_coord = '';
    }
} 
?>

<div class="container-fluid cor-bg">
    <div class="row">
        <div class="col-auto">
            <img src="<?= URL ?>/public//images/fabinho1.jpg" class="img-fluid" width="100px" alt="Imagem responsiva">
        </div>
        <div class="col-auto">
            <h3 class="text-light">DEPUTADO ESTADUAL FABINHO</h3>
            <!-- <h5 class="text-light"><?= APP_NOME_SUB_TITULO ?> - <?= $user['acesso'] . $nome_coord ?></h5> -->
            <h5 class="text-light"><?= APP_NOME_SUB_TITULO ?></h5>
        </div>
    </div>

</div>