<?php

class Mensagem extends Controller
{

    private $mensagemModel;
    private $sessao_acesso;
    private $home;

    public function __construct()
    {

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

        $this->mensagemModel = $this->model('MensagemModel');
    }

    public function index()
    {

        $users = $this->list_users();

        $dados = [
            'titulo' => 'Mensagens',
            'users'  => $users,
            'id_outro' => '',
            'meu_id'    => $_SESSION['user']['id'],
            'home'      => $this->home
        ];
        // echo json_encode($users);
        $this->view('mensagem/index', $dados);
    }

    public function select_user($id_destino = null, $nome_destino = null, $id_msg = null)
    {


        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            if ($id_destino && $nome_destino) {
                $id_outro = $id_destino;
                $nome_outro = $nome_destino;
            } else {
                $id_outro = $form['id_outro'];
                $nome_outro = $form['nome_outro'];
            }


            $id_user = $_SESSION['user']['id'];

            $mensagens = '';

            $mensagens_res = $this->mensagemModel->getMensagens($id_user, $id_outro);
            if ($mensagens_res['erro'] == '') {
                $mensagens = $mensagens_res['mensagens'];

                //Alterar Lida
                $nao_lidas = false;
                foreach ($mensagens as $m) {
                    //se tiver alguma mensagen não lida
                    if ($m['lida'] == 0) {
                        $nao_lidas = true;
                        break;
                    }
                }

                //Modifica o status (LIDA) das mensagens
                if ($id_msg && $nao_lidas) {
                    // alterar todas as mensagens para Lida com exceção da que foi enviada agora
                    $this->mensagemModel->updateLidaAnteriores($id_user, $id_outro, $id_msg);
                }
                if (!$id_msg && $nao_lidas) {
                    $this->mensagemModel->updateLida($id_user, $id_outro);
                }
            }
        }

        $users = $this->list_users();

        $dados = [
            'mensagens' => $mensagens,
            'users'     => $users,
            'meu_id'    => $id_user,
            'id_outro'  => $id_outro,
            'nome_outro' => $nome_outro,
            'home'      => $this->home
        ];

        // echo json_encode($mensagens);
        $this->view('mensagem/index', $dados);
    }

    public function send()
    {
        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        if (isset($form)) {
            $dados = [
                'id_origem' => $_SESSION['user']['id'],
                'id_destino' => $form['id_destino'],
                'mensagem'   => $form['mensagem']
            ];

            if ($dados['mensagem'] != '') {
                $id_msg_res = $this->mensagemModel->insert($dados);
                if ($id_msg_res['erro'] == '') {
                    $id_msg = $id_msg_res['id_mensagem'];
                } else {
                    echo 'ERRO ao inserir mensagem!';
                }
                $this->select_user($form['id_destino'], $form['nome_destino'], $id_msg);
            } else {
                $this->select_user($form['id_destino'], $form['nome_destino']);
            }
        }
    }

    public function list_users()
    {

        $users = '';
        $id_user = $_SESSION['user']['id'];

        $list_users = $this->mensagemModel->listUsers();
        if ($list_users['erro'] == '') {
            $users = $list_users['list'];
        }

        return $users;
    }

    public function solicita_ajax()
    {

        $acao = filter_input(INPUT_POST, 'action');
        $id_outro = filter_input(INPUT_POST, 'id_outro');

        $list_users = $this->mensagemModel->listUsers($id_outro);
        if ($list_users['erro'] == '') {
            $users = $list_users['list'];
        }

        echo json_encode($users);
    }
}
