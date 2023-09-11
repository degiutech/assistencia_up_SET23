<?php

class Diversos extends Controller
{

    private $home;
    private $sessao_acesso;

    private $userModel;
    private $cidadaoModel;
    private $coordenacaoModel;
    private $assistenciaModel;
    private $cidadaoController;
    private $representanteController;
    private $assistenciaController;

    public function __construct()
    {

        if (!Sessao::estaLogado()) :
        // URL::redirect('users/login_email');
        endif;

        if (isset($_SESSION['user'])) {

            $this->sessao_acesso = Sessao::sessaoUser();

            if ($this->sessao_acesso['acesso'] == 'Administração') {
                $this->home = URL . '/admin';
            }
            if ($this->sessao_acesso['acesso'] == 'Supervisão') {
                $this->home = URL . '/supervisao';
            }
            if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
                $this->home = URL . '/coordenacao';
            }
            if ($this->sessao_acesso['acesso'] == 'Representante') {
                $this->home = URL . '/representante';
            }
        }

        $this->userModel = $this->model('UserModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');

        $this->cidadaoController = $this->controller('Cidadao');
        $this->representanteController = $this->controller('Representante');
        $this->assistenciaController = $this->controller('Assistencias');
    }

    //Função redirecionada
    public function create_assistencia($id)
    {
        $this->assistenciaController->create($id);
    }
}
