<?php

class Ajax extends Controller
{

    private $cidadaoModel;
    private $userModel;
    private $operadorModel;

    public function __construct()
    {

        // if (!Sessao::estaLogado()) :
        //     URL::redirect('users/login_email');
        // endif;

        $this->cidadaoModel = $this->model('CidadaoModel');
        $this->userModel = $this->model('UserModel');
        $this->operadorModel = $this->model('OperadorModel');
    }

    public function busca_cidadao()
    {

        $texto = filter_input(INPUT_POST, 'texto');

        echo $this->cidadaoModel->search($texto);
    }

    //busca operador por nome
    public function busca_operador_by_nome()
    {

        $texto = filter_input(INPUT_POST, 'texto');
        $id_coordenadoria = filter_input(INPUT_POST, 'id_coordenadoria');

        echo $this->operadorModel->buscaOperadorByNome($texto, $id_coordenadoria);
    }
}
