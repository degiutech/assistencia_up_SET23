<?php

if (isset($_SESSION['user'])) {

    $acesso = $_SESSION['user']['acesso'];

    if ($acesso == 'Administração') {
        return $this->view('admin/index');
    }
    if ($acesso == 'Supervisão') {
        return $this->view('supervisao/index');
    }
    if ($acesso == 'Coordenadoria') {
        return $this->view('coordenacao/index');
    }
    if ($acesso == 'Representante') {
        return $this->view('representante/index');
    }
}
