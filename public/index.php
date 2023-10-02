<?php

session_start();

$app = '';
$acesso = '';

if (isset($_SESSION['user'])) {

  if ($_SESSION['user']['app'] == 'fabinho') {
    $app = 'fabinho';
  }
  if ($_SESSION['user']['app'] == 'degiutech') {
    $app = 'degiutech';
  }

  $sessao_acesso = $_SESSION['user']['acesso'];

  if ($sessao_acesso == 'Administração') {
    $acesso = 'admin';
  }
  if ($sessao_acesso == 'Supervisão') {
    $acesso = 'supervisao';
  }
  if ($sessao_acesso == 'Coordenadoria') {
    $acesso = 'coordenacao';
  }
  if ($sessao_acesso == 'Representante') {
    $acesso = 'representante';
  }
}


date_default_timezone_set('America/Cuiaba');
include './../app/phperror.php';
include './../app/config.php';
include './../app/autoload.php';

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NOME ?></title>

  <link href="<?= URL ?>/public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

  <link href="<?= URL ?>/public/css/styles.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-print-2.2.2/date-1.1.2/r-2.2.9/datatables.min.js"></script>

</head>

<!-- #50acff #f1f2f4 -->

<body style="background-color: #f1f2f4;">

  <!-- Div para print -->
  <div id="div_print_geral" style="display: none; min-height: 100vh;" class="container-fluid card">

    <div class="card-body">

      <div id="conteudo_print"></div>

    </div>

  </div>

  <div id="div_navs" class="esconder_para_print">

    <?php

    if (isset($_SESSION['user'])) {

      if ($app == 'fabinho') {
        include '../app/Views/barra_superior.php';
      }
      if ($app == 'degiutech') {
        include '../app/Views/nav_admin0.php';
      }

      $chat = filter_input(INPUT_GET, 'chat');

      if ($acesso == 'admin' && !isset($chat)) {
        include '../app/Views/nav_admin.php';
      }
      if ($acesso == 'supervisao' && !isset($chat)) {
        include '../app/Views/nav_super.php';
      }
      if ($acesso == 'coordenacao' && !isset($chat)) {
        include '../app/Views/nav_coord.php';
      }
      if ($acesso == 'representante' && !isset($chat)) {
        include '../app/Views/nav_rep.php';
      }
    }

    ?>

  </div>

  <div class="mb-5" style="min-height: 360px;">
    <div style="display: none;">inicio_page_ajax</div>
    <?php
    $routes = new Rotas();
    ?>
    <div style="display: none;">fim_page_ajax</div>

  </div>

  <div id="div_do_rodape" class="esconder_para_print">

    <?php

    if (isset($_SESSION['user'])) {
      //  include '../app/Views/chat.php';
    }

    if (isset($_SESSION['user']) && $_SESSION['user']['app'] == 'fabinho') {
      include '../app/Views/rodape.php';
    }
    ?>

  </div>

  <script src="<?= URL ?>/public/js/mask-jquery/src/jquery.mask.js"></script>

  <script src="<?= URL ?>/public/bootstrap/dist/js/bootstrap.bundle.js"></script>
  <script src="<?= URL ?>/public/js/jquery.funcoes.js"></script>

</body>

</html>

<script>
  // Tooltip
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  if ($("#pag_chat").val() == "pag_chat") {
    $("#div_do_rodape").hide()
    $("#div_navs").hide()
  }
</script>