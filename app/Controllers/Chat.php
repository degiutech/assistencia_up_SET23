<?php

class Chat extends Controller
{

    private $sessao_acesso;
    private $home;
    private $acesso;
    private $chatModel;
    private $userModel;

    public function __construct()
    {

        if (!Sessao::estaLogado()) {
            $dados = [
                'email' => '',
                'email_erro' => ''
            ];
            return $this->view('users/login_email', $dados);
        }

        $this->sessao_acesso = Sessao::sessaoUser();

        if ($this->sessao_acesso['acesso'] == 'Administração') {
            $this->home = URL . '/admin/index';
            $this->acesso = 'Administração';
        }
        if ($this->sessao_acesso['acesso'] == 'Supervisão') {
            $this->home = URL . '/supervisao/index';
            $this->acesso = 'Supervisão';
        }
        if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
            $this->home = URL . '/coordenacao/index';
            $this->acesso = 'Coordenadoria';
        }
        if ($this->sessao_acesso['acesso'] == 'Representante') {
            $this->home = URL . '/representante/index';
            $this->acesso = 'Representante';
        }

        $this->chatModel = $this->model('ChatModel');
        $this->userModel = $this->model('UserModel');
    }

    public function index()
    {

        $dados = [
            'home' => $this->home
        ];

        // $this->view('chat/chat', $dados);
        $this->chat();
    }

    public function chat()
    {

        $id_user = $_SESSION['user']['id'];

        $users = [];
        $users_res = $this->userModel->allUsersChat();
        if ($users_res['erro'] == '') {

            $users = $users_res['users'];
            for ($i = 0; $i < count($users); $i++) {

                // $users[$i]['id'] != $id_user;

                //Pega o id da sala
                $id_sala = $this->chatModel->otherUserSala($users[$i]['id'], $id_user);
                
                if ($id_sala != '') {
                    $users[$i]['id_sala'] = $id_sala;
                    //Pega o status de leitura da sala e o id do emissor
                    $leitura_emissor_sala = $this->chatModel->statusLeituraEmissorSala($id_sala);
                    $users[$i]['leitura'] = $leitura_emissor_sala['leitura'];
                    $users[$i]['id_emissor'] = $leitura_emissor_sala['id_emissor'];
                    
                } else {
                    $users[$i]['id_sala'] = '';
                    $users[$i]['leitura'] = '';
                    $users[$i]['id_emissor'] = '';
                }
            }
        }

        $mesages = '';

        $dados = [
            'home' => $this->home,
            'id_user' => $id_user,
            'name' => $_SESSION['user']['nome'],
            'messages' => $mesages,
            'users'    => $users
        ];

        $this->view('chat/chat', $dados);
    }
}
