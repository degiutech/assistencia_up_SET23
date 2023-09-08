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
    }

    public function create_assistencia($id) {
        $all_coordenadorias = $this->coordenacaoModel->allCoordenadorias();
        if ($all_coordenadorias['erro'] == '' && $all_coordenadorias['coordenadorias'] != '') {
            $coordenadorias = $all_coordenadorias['coordenadorias'];

            $user_id_coordenadoria = $_SESSION['user']['id_coordenadoria'];
        } else {
            $coordenadorias = 'Nenhuma Coordenadoria encontrada!';
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $date_at = trim($form['date_at']);
            $dt = new DateTime($date_at);
            $dia = $dt->format('d');
            $mes = $dt->format('m');
            $ano = $dt->format('Y');

            $dados = [
                'id_cidadao' => trim($form['id_cidadao']),
                'nome_cidadao' => trim($form['nome_cidadao']),
                'name_created_by' => $_SESSION['user']['nome'],
                'id_created_by' => $_SESSION['user']['id'],

                'descricao' => trim($form['descricao']),
                'descricao_complemento' => trim($form['descricao_complemento']),
                'coordenadorias' => $coordenadorias,
                'id_coordenadoria' => $form['id_coordenadoria'],
                'nome_coordenadoria_selecionada' => $form['nome_coordenadoria_selecionada'],
                'desc_juridica'      => trim($form['descricao_juridica']),
                'num_proc_juridica'  => $form['proc_juridico'],
                'status_assist'      => 'Iniciada',
                'status_complemento' => 'Assistência Iniciada',
                'date_at'             => $date_at,
                'dia'                   => $dia,
                'mes'                   => $mes,
                'ano'                   => $ano,
                'hora'             => date('H:i'),
                'user_id_coordenadoria' => $user_id_coordenadoria,
                'home'      => $this->home,

                'descricao_erro' => '',
                'descricao_complemento_erro' => '',
                'id_coordenadoria_erro' => '',
                'status_assist_erro' => ''
            ];



            $erro = '';

            if ($dados['id_coordenadoria'] == 0) {
                $dados['id_coordenadoria_erro'] = 'Selecione uma Coordenadoria!';
                $erro = 'erro';
            } else {
                $res_nome_coord = $this->coordenacaoModel->getNomeCoordenadoria($dados['id_coordenadoria']);
                $dados['nome_coordenadoria_selecionada'] = $res_nome_coord['nome']['nome'];
            }

            // Cadastra a Assistência
            if ($erro == '') {

                $cadastro = $this->assistenciaModel->create($dados);

                if ($cadastro['existe'] == 'existe') {

                    Sessao::mensagem('assistencia', 'Assistência existente!', 'alert alert-danger');

                    return $this->cidadaoController->cidadao($id);
                }
                if ($cadastro['erro'] == '' && $cadastro['id_assistencia'] != '') {

                    $dados_up = [
                        'id_assistencia' => $cadastro['id_assistencia'],
                        'id_coordenadoria' => $dados['id_coordenadoria'],
                        'nome_coordenadoria' => $dados['nome_coordenadoria_selecionada'],
                        'id_updated_by' => $_SESSION['user']['id'],
                        'name_updated_by' => $_SESSION['user']['nome'],
                        'status_updated'  => 'Iniciada',
                        'status_compl_updated' => 'Assistência Iniciada',
                    ];

                    // assistencia_update
                    $cadastro_up = $this->assistenciaModel->createUp($dados_up);
                    if ($cadastro_up['erro'] !== '') {

                        $remove_assistencia = $this->assistenciaModel->removeAssistencia($cadastro['id_assistencia']);

                        Sessao::mensagem('assistencia', 'ERRO ao registrar Assistência', 'alert alert-danger');
                    } else {

                        Sessao::mensagem('assistencia', 'Assistência registrada com sucesso!');
                    }

                    return $this->cidadaoController->cidadao($id);
                } else {
                    Sessao::mensagem('assistencia', 'ERRO ao registrar Assistência, tente mais tarde!', 'alert alert-danger');
                }
            }
        } else {

            $dados_cidadao = $this->cidadaoModel->getNomeIdCidadao($id);
            if ($dados_cidadao['erro'] == '' && $dados_cidadao['cidadao'] != '') {

                $id_cidadao = $dados_cidadao['cidadao']['id'];
                $nome_cidadao = $dados_cidadao['cidadao']['nome'];
            }

            $dados = [
                'id_cidadao' => $id_cidadao,
                'nome_cidadao' => $nome_cidadao,
                'name_created_by' => $_SESSION['user']['nome'],
                'id_created_by' => $_SESSION['user']['id'],

                'descricao' => '',
                'descricao_complemento' => '',
                'coordenadorias' => $coordenadorias,
                'nome_coordenadoria_selecionada' => '',
                'id_coordenadoria' => 0,
                'status_assist' => 0,
                'status_complemento' => '',
                'date_at'   => date('Y-m-d'),
                'hora'   => date('H:i'),
                'user_id_coordenadoria' => $user_id_coordenadoria,
                'home'      => $this->home,

                'descricao_erro' => '',
                'descricao_complemento_erro' => '',
                'id_coordenadoria_erro' => '',
                'status_assist_erro' => ''
            ];
        }

        $this->view('assistencias/create', $dados);
    }

}