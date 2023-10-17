<?php

class Users extends Controller
{

    private $sessao_acesso;
    private $home;

    private $userModel;
    private $operadorModel;
    private $coordenacaoModel;
    private $assistenciaModel;

    private $dgUserModel;
    private $dgAssistenciaModel;

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

        $this->userModel = $this->model('UserModel');
        $this->operadorModel = $this->model('OperadorModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');

        $this->dgUserModel = $this->model('DgUserModel');
        $this->dgAssistenciaModel = $this->model('DgAssistenciaModel');
    }

    public function index()
    {
        $dados = [
            'tituloPagina' => 'Página Users Index',
            'descricao' => 'blablaC',
            'email' => '',
            'email_erro' => ''
        ];

        if (!Sessao::estaLogado()) :
            Url::redirect('users/login_email');
        else :
            $this->view($this->home);
        endif;
    }

    public function login_email()
    {

        $sistema_res = $this->dgAssistenciaModel->getSistema(1);
        if ($sistema_res['erro'] == '') {
            if (!$sistema_res['sistema']['bloqueio']) {
                return $this->sistema_suspenso($sistema_res['sistema']);
            }
        }

        $dados = [
            'email' => '',
            'email_erro' => ''
        ];

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :

            if (isset($_SESSION['user'])) {
                // Url::redirect($this->logout());
                return $this->logout();
            }

            if (empty(trim($form['email']))) :
                $dados['email_erro'] = 'Campo email não pode ser vazio!';
                return $this->view('users/login_email', $dados);

            elseif (!empty(trim($form['email'])) && Check::checkEmail(trim($form['email']))) :
                $dados['email_erro'] = 'Digite um email válido!';
                return $this->view('users/login_email', $dados);

            endif;

            // DEGIUTECH
            if (trim($form['email']) == EMAIL_DEGIU) {
                Url::redirect('dguser/login_dg');
            }

            $check = $this->userModel->checkEmail(trim($form['email']));

            if ($check) :
                $dados = [
                    'email' => trim($form['email']),
                    'email_erro' => '',
                    'pass'       => '',
                    'pass_erro'  => ''
                ];
                return $this->view('users/login', $dados);
            else :
                $dados = [
                    'email' => trim($form['email']),
                    'email_erro' => 'Este email não está registrado!',
                    'pass'       => '',
                    'pass_erro'  => ''
                ];
                return $this->view('users/login_email', $dados);
            endif;

        else :

            $dados = [
                'email' => '',
                'email_erro' => '',
            ];

        endif;

        $this->view('users/login_email', $dados);
    }

    public function login()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :

            $dados = [
                'email' => trim($form['email']),
                'pass' => trim($form['pass']),
                'email_erro' => '',
                'pass_erro' => '',
            ];

            if (empty($form['email'])) :
                return $this->logout();
            endif;

            if (in_array("", $form)) :

                if (empty($form['pass'])) :
                    $dados['pass_erro'] = 'Preencha o campo senha';
                endif;

            else :

                $check = $this->userModel->checkLogin($form['email'], $form['pass']);
                if ($check['erro'] != '' && $check['id'] == '') :
                    Sessao::mensagem('user', $check['erro'], 'alert alert-danger');
                else :
                    //Pegar usuário e iniciar sessão
                    $user = $this->userModel->readUserById($check['id']);
                    if ($user['user']['bloqueio'] == 'Desbloqueado') :
                        return $this->createSessionUser($user['user']);
                    else :
                        Sessao::mensagem('user', 'Acesso bloqueado! Por favor, entre em contato com o administrador!', 'alert alert-danger');
                    endif;

                endif;
            endif;
        else :


            $dados = [
                'email' => '',
                'pass' => '',
                'email_erro' => '',
                'pass_erro' => '',
            ];
        endif;

        $this->view('users/login', $dados);
    }

    private function createSessionUser($user)
    {


        if (!isset($user['nome_coordenadoria'])) {
            $nome_coordenadoria = '';
        } else {
            $nome_coordenadoria = $user['nome_coordenadoria'];
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'acesso' => $user['acesso'],
            'bloqueio' => $user['bloqueio'],
            'id_coordenadoria' => $user['id_coordenadoria'],
            'nome_coordenadoria' => $nome_coordenadoria,
            'app' => 'fabinho'
        ];

        $acesso = $_SESSION['user']['acesso'];

        if ($acesso == 'Administração') {
            Url::redirect('admin/index');
        }
        if ($acesso == 'Supervisão') {
            Url::redirect('supervisao/index');
        }
        if ($acesso == 'Coordenadoria') {
            Url::redirect('coordenacao/index');
        }
        if ($acesso == 'Representante') {
            Url::redirect('representante/index');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);

        session_destroy();

        $this->view('users/logout');
    }

    //Devolver acesso negado para index
    public function index_acesso()
    {

        $acesso = $_SESSION['user']['acesso'];

        if ($acesso == 'Administração') {
            $index = URL . '/admin/index';
        }
        if ($acesso == 'Supervisão') {
            $index = URL . '/supervisao/index';
        }
        if ($acesso == 'Coordenadoria') {
            $index = URL . '/coordenacao/index';
        }
        if ($acesso == 'Representante') {
            $index = URL . '/representante/index';
        }

        $dados = ['index' => $index];

        $this->view('users/index_acesso', $dados);
    }

    public function create()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        unset($_SESSION['dados_novo_cadastro']);

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {
            $dados = [
                'nome' => trim($form['nome']),
                'email' => trim($form['email']),
                'celular' => trim($form['celular']),

                'nome_erro' => '',
                'email_erro' => '',
                'celular_erro' => '',
            ];

            $erro = '';
            if (empty(trim($form['nome'])) || trim($form['nome']) == "") {
                $dados['nome_erro'] = 'Preencha campo nome!';
                $erro = 'erro';
            }
            if (!empty(trim($form['nome'])) && strlen($form['nome']) > 50 || strlen($form['nome']) < 2) {
                $dados['nome_erro'] = 'O nome deve conter entre 2 e 50 caracteres!';
                $erro = 'erro';
            }
            if (empty($dados['email'])) {
                $dados['email_erro'] = 'É preciso informar o email!';
                $erro = 'erro';
            }
            if (Check::checkEmail(trim($dados['email']))) {
                $dados['email_erro'] = 'Forneça um email válido!';
                $erro = 'erro';
            }
            if ($this->userModel->checkEmail(trim($dados['email']))) {
                $dados['email_erro'] = 'Este email já está registrado!';
                $erro = 'erro';
            }
            if (empty(trim($dados['celular']))) {
                $dados['celular_erro'] = 'Informe o número do celular!';
                $erro = 'erro';
            }
            if (!empty(trim($dados['celular'])) && strlen(trim($dados['celular'])) < 15) {
                $dados['celular_erro'] = 'Número incompleto';
                $erro = 'erro';
            }
            if ($erro == '') {

                $_SESSION['dados_novo_cadastro'] = $dados;

                $dados = [
                    'nome'                 => $dados['nome'],
                    'cep'                  => '',
                    'logradouro'           => '',
                    'numero'               => '',
                    'complemento'          => '',
                    'bairro'               => '',
                    'cidade'               => '',
                    'uf'                   => '',
                    'ibge'                 => '',
                    'local_trabalho'       => '',
                    'cidade_trabalho'      => '',
                    'uf_trabalho'          => '',

                    'cep_erro'             => '',
                    'logradouro_erro'      => '',
                    'numero_erro'          => '',
                    'complemento_erro'     => '',
                    'bairro_erro'          => '',
                    'cidade_erro'              => '',
                    'uf_erro'              => '',
                    'ibge_erro'            => '',
                    'local_trabalho_erro'  => '',
                    'cidade_trabalho_erro' => '',
                    'uf_trabalho_erro'     => '',
                ];

                return $this->view('users/update_endereco', $dados);
            }
        } else {
            $dados = [
                'nome' => '',
                'email' => '',
                'celular' => '',

                'nome_erro' => '',
                'email_erro' => '',
                'celular_erro' => '',
            ];
        }
        $this->view('users/create', $dados);
    }

    /**
     * Edita ou altera endereço de operador
     */
    public function update_endereco()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :

            $dados = [
                'cep'              => trim($form['cep']),
                'logradouro'       => trim($form['logradouro']),
                'numero'           => trim($form['numero']),
                'complemento'      => trim($form['complemento']),
                'bairro'           => trim($form['bairro']),
                'cidade'           => trim($form['cidade']),
                'uf'               => trim($form['uf']),
                'ibge'             => trim($form['ibge']),
                'local_trabalho'   => trim($form['local_trabalho']),
                'cidade_trabalho'  => trim($form['cidade_trabalho']),
                'uf_trabalho'      => trim($form['uf_trabalho']),
                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => ''
            ];

            $erro = '';

            //tratar os erros
            if (strlen(trim($form['cep'])) > 0 && strlen(trim($form['cep'])) < 9) :
                $dados['cep_erro'] = 'CEP incompleto!';
                $erro = 'erro';
            endif;

            if ($erro == '') :
                $_SESSION['dados_novo_cadastro']['cep'] = $dados['cep'];
                $_SESSION['dados_novo_cadastro']['logradouro'] = $dados['logradouro'];
                $_SESSION['dados_novo_cadastro']['numero'] = $dados['numero'];
                $_SESSION['dados_novo_cadastro']['complemento'] = $dados['complemento'];
                $_SESSION['dados_novo_cadastro']['bairro'] = $dados['bairro'];
                $_SESSION['dados_novo_cadastro']['cidade'] = $dados['cidade'];
                $_SESSION['dados_novo_cadastro']['uf'] = $dados['uf'];
                $_SESSION['dados_novo_cadastro']['ibge'] = $dados['ibge'];
                $_SESSION['dados_novo_cadastro']['local_trabalho'] = $dados['local_trabalho'];
                $_SESSION['dados_novo_cadastro']['cidade_trabalho'] = $dados['cidade_trabalho'];
                $_SESSION['dados_novo_cadastro']['uf_trabalho'] = $dados['uf_trabalho'];

                $coords = $this->coordenacaoModel->allCoordenadorias();
                if ($coords['erro'] == '' && $coords['coordenadorias'] != '') {
                    $coordenadorias = $coords['coordenadorias'];
                    $_SESSION['dados_novo_cadastro']['coordenadorias'] = $coords['coordenadorias'];
                }

                $dados = [
                    'nome' => $_SESSION['dados_novo_cadastro']['nome'],
                    'id' => '',
                    'acesso'           => $this->sessao_acesso,
                    'permissao' => $this->sessao_acesso['acesso'],
                    'coordenadorias' => $coordenadorias,
                    'nova_coordenadoria' => '',
                    'id_coordenadoria' => '',
                    'pass' => '',
                    'confirm-pass' => '',
                    'pass_erro' => '',
                    'confirm-pass_erro' => '',
                ];

                // Se cadastro está sendo feito por Coordenador
                if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {

                    $_SESSION['dados_novo_cadastro']['id_coordenadoria'] = '';
                    $_SESSION['dados_novo_cadastro']['nova_coordenadoria'] = '';
                    $_SESSION['dados_novo_cadastro']['acesso'] = 'Representante';

                    $dados = [
                        'id' => '',
                        'pass' => '',
                        'confirm-pass' => '',
                        'pass_erro' => '',
                        'confirm-pass_erro' => '',
                    ];

                    return $this->view('users/pass_register', $dados);
                }

                return $this->view('users/config_acesso_create', $dados);

            endif;

        else :

            $dados = [
                'nome' => $_SESSION['dados_novo_cadastro']['nome'],
                'acesso'           => $this->sessao_acesso,
                'cep'              => '',
                'logradouro'       => '',
                'numero'           => '',
                'complemento'      => '',
                'bairro'           => '',
                'cidade'           => '',
                'uf'               => '',
                'ibge'             => '',
                'local_trabalho'   => '',
                'cidade_trabalho'  => '',
                'uf_trabalho'      => '',

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',
                'local_trabalho_erro'   => '',
                'cidade_trabalho_erro'  => '',
                'uf_trabalho_erro'      => '',
            ];

        endif;

        $this->view('users/update_endereco', $dados);
    }

    //Congurar acesso de operador no cadasto de novo operador
    public function config_acesso_create()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $coords = $this->coordenacaoModel->allCoordenadorias();

        if ($coords['erro'] == '' && $coords['coordenadorias'] != '') {
            $coordenadorias = $coords['coordenadorias'];
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $acesso = '';
            if (isset($form['check_acesso'])) {
                $acesso = $form['check_acesso'];
            }

            $dados = [
                'id_coordenadoria'         => $form['coordenadoria'],
                'input_nova_coordenadoria' => trim($form['input_nova_coordenadoria']),
                'acesso'                   => $acesso,
                'nome'                     => $_SESSION['dados_novo_cadastro']['nome'],
                'permissao'                => $this->sessao_acesso['acesso'],
                'coordenadorias'           => $_SESSION['dados_novo_cadastro']['coordenadorias'],
                'nova_coordenadoria'       => trim($form['input_nova_coordenadoria'])
            ];

            // Validação
            $erro = '';
            if (empty($dados['acesso'])) {
                Sessao::mensagem('user', 'É preciso informar o Acesso!', 'alert alert-danger');
                $dados['acesso'] = '';
                $erro = 'erro';
            }
            //Coordenador
            if ($dados['acesso'] == 'Coordenadoria' && $dados['id_coordenadoria'] == 'Selecione') {
                Sessao::mensagem('user', 'Para acesso do tipo Coordenador,  preciso informar a Coordenadoria!', 'alert alert-danger');
                $erro = 'erro';
            }
            if ($dados['acesso'] == 'Coordenadoria' && $dados['id_coordenadoria'] == 'nova' && empty($dados['nova_coordenadoria'])) {
                Sessao::mensagem('user', ' preciso informar o nome da nova Coordenadoria!', 'alert alert-danger');
                $erro = 'erro';
            }
            //Nova Coordenadoria
            if ($dados['id_coordenadoria'] == 'nova' && $dados['nova_coordenadoria'] != '') {
                $dados_coord = ['nome' => $dados['nova_coordenadoria']];
                $nova_coord = $this->coordenacaoModel->novaCoordenadoria($dados_coord);
                if ($nova_coord == 'Esta Coordenadoria já existe!') {
                    Sessao::mensagem('user', $nova_coord, 'alert alert-danger');
                    $erro = 'erro';
                } else {
                    $dados['id_coordenadoria'] = $nova_coord['id'];
                }
            }

            //Lógica e Persistência
            if ($erro == '') {

                $_SESSION['dados_novo_cadastro']['id_coordenadoria'] = $dados['id_coordenadoria'];
                $_SESSION['dados_novo_cadastro']['nova_coordenadoria'] = $dados['nova_coordenadoria'];
                $_SESSION['dados_novo_cadastro']['acesso'] = $dados['acesso'];

                $dados = [
                    'id' => '',
                    'pass' => '',
                    'confirm-pass' => '',
                    'pass_erro' => '',
                    'confirm-pass_erro' => '',
                ];

                return $this->view('users/pass_register', $dados);
            }
        } else {

            $dados = [
                'nome' => $_SESSION['dados_novo_cadastro']['nome'],
                'id' => '',
                'id_coordenadoria' => '',
                'permissao' => $this->sessao_acesso['acesso'],
                'coordenadorias' => $coordenadorias,
                'acesso'         => '',
                'nova_coordenadoria' => ''
            ];
        }

        $this->view('users/config_acesso_create', $dados);
    }

    public function pass_register()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :
            $dados = [
                'id' => trim($form['id']),
                'pass' => trim($form['pass']),
                'confirm-pass' => trim($form['confirm-pass']),
                'pass_erro' => '',
                'confirm-pass_erro' => '',
            ];

            $erro = '';

            if (empty($form['confirm-pass'])) :
                $dados['confirm-pass_erro'] = 'Confirme a senha';
                $erro = 'erro';
            endif;

            if (strlen($form['pass']) < 6) :
                $dados['pass_erro'] = 'A senha deve ter no mínimo 6 caracteres.';
                $erro = 'erro';
            endif;

            if ($form['pass'] != $form['confirm-pass']) :
                $dados['confirm-pass_erro'] = 'As senhas devem ser iguais.';
                $erro = 'erro';
            endif;

            if ($erro == '') :

                $_SESSION['dados_novo_cadastro']['pass'] = $dados['pass'];

                //CADASTRAR USUÁRIO
                $cadastro = $this->userModel->create($_SESSION['dados_novo_cadastro']);
                if ($cadastro['erro'] == '' && $cadastro['id'] != '') :

                    unset($_SESSION['dados_novo_cadastro']);

                    $dados = [
                        'id_operador' => $cadastro['id'],
                        'id_updated_by' => $_SESSION['user']['id'],
                        'name_updated_by' => $_SESSION['user']['nome'],
                        'updated_what'    => 'Cadastro'
                    ];

                    $user_update = $this->operadorModel->usersUpdateActions($dados);

                    if ($user_update['erro_users_update'] == '' && $user_update['affected_rows_users_update'] == 1) {
                        Sessao::mensagem('user', 'Operador cadastrado com sucesso!');
                    }

                    return $this->info_operador($cadastro['id']);

                endif;

            endif;

        else :

            $dados = [
                'id' => '',
                'pass' => '',
                'confirm-pass' => '',
                'pass_erro' => '',
                'confirm-pass_erro' => '',
            ];

        endif;

        $this->view('users/pass_register', $dados);
    }

    public function config_operador($id = null)
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $res = $this->operadorModel->getInfoOperator($id);

        if ($res['erro'] == '' && $res['operador'] != '') {
            $op = $res['operador'];

            $dados = [
                'id'               => $id,
                'nome'             => $op['nome'],
                'acesso'           => $op['acesso'],
                'id_coordenadoria' => $op['id_coordenadoria'],
                'bloqueio'         => $op['bloqueio'],
                'permissao'        => $this->sessao_acesso['acesso']
            ];
        } else {
            echo $res['erro'];
        }

        $coordenadorias = $this->coordenacaoModel->allCoordenadorias();
        if ($coordenadorias['erro'] == '' && $coordenadorias['coordenadorias'] != '') {
            $dados['coordenadorias'] = $coordenadorias['coordenadorias'];
        }

        $dados['home'] = $this->home;

        $this->view('users/config_operador', $dados);
    }

    //Configurações para operador já cadastrado
    public function config_op_acesso()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id'       => $form['id'],
                'nome'     => $form['nome'],
                'acesso'   => $form['check_acesso'],
            ];

            $acesso = $form['check_acesso'];

            //Acesso Administração e Supervisão
            if ($acesso == 'Administraço' || $acesso == "Supervisão") {

                $dados['id_coordenadoria'] = 0;

                $update = $this->operadorModel->updateAdminSuperConfig($dados);

                if ($update['erro'] == '' && $update['affected_rows'] == 1) {

                    Sessao::mensagem('user', 'Operador configurado com sucesso!');

                    return $this->info_operador($update['id']);
                }
            }

            //Acesso Coordenação
            if ($acesso == 'Coordenadoria') {

                //Nova Coordenadoria
                if ($form['coordenadoria'] == 'nova' && !empty($form['input_nova_coordenadoria'])) {
                    $dados_coord = [
                        'nome' => $form['input_nova_coordenadoria'],
                        'created_by' => $_SESSION['user']['nome']
                    ];

                    $coordenadoria = $this->coordenacaoModel->novaCoordenadoria($dados_coord);

                    if ($coordenadoria['erro'] == '' && $coordenadoria['id'] != '') {
                        $dados['id_coordenadoria'] = $coordenadoria['id'];

                        //Configura o Operador
                        $update = $this->operadorModel->updateCoordenadorConfig($dados);

                        if ($update['erro'] == '' && $update['affected_rows'] == 1) {

                            Sessao::mensagem('user', 'Operador configurado com sucesso!');
                        } else {
                            Sessao::mensagem('user', 'ERRO ao configurar o Operador: ' . $update['erro'], 'alert alert-danger');
                        }

                        return $this->info_operador($update['id']);
                    } else {
                        Sessao::mensagem('user', 'ERRO em Nova Coordenadoria! - ' . $coordenadoria['erro']);
                        return $this->info_operador($update['id']);
                    }
                } else {
                    //Configura sem uma nova coordenadoria
                    $dados['id_coordenadoria'] = $form['coordenadoria'];

                    $update = $this->operadorModel->updateCoordenadorConfig($dados);

                    if ($update['erro'] == '' && $update['affected_rows'] == 1) {

                        Sessao::mensagem('user', 'Operador configurado com sucesso!');

                        return $this->info_operador($update['id']);
                    }
                }
            }

            //Acesso Representante
            if ($acesso == 'Representante') {

                $update = $this->operadorModel->updateRepresentanteConfig($dados);



                if ($update['erro'] == '' && $update['affected_rows'] == 1) {

                    Sessao::mensagem('user', 'Representante configurado com sucesso!');

                    return $this->info_operador($update['id']);
                }
            }
        }
    }

    public function bloqueio_desbloqueio()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $dados = [
            'id' => trim($form['id']),
            'bloqueio' => trim($form['bloqueio'])
        ];

        $res = $this->operadorModel->bloqueioDesbloqueio($dados);

        if ($res['erro'] == '' && $res['affected_rows'] == 1) {
            Sessao::mensagem('user', 'Acesso ' . $res['bloqueio'] . ' com sucesso!');
        } else {
            Sessao::mensagem('user', 'ERRO ao bloquear/desbloquear acesso, tente mais tarde!');
        }
        return $this->config_operador($res['id']);
    }

    public function info_operador($id)
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $op = $this->operadorModel->getInfoOperator($id);

        if ($op['erro'] == '' && $op['operador'] != '') {

            //nome de coordenadoria
            if (!empty($op['operador']['id_coordenadoria'])) {

                $nome_coord = $this->coordenacaoModel->getcoordenadoria($op['operador']['id_coordenadoria']);
                if ($nome_coord['erro'] == '' && $nome_coord['coordenadoria'] != '') {
                    $op['operador']['nome_coordenadoria'] = $nome_coord['coordenadoria']['nome'];
                }
            }

            $dados = [
                'id' => $id,
                'op' => $op['operador'],
                'home' => $this->home
            ];

            $count_todas = 0;
            $count_Primeiro_registro = 0;
            $count_atualizacoes = 0;
            $count_finalizacoes = 0;

            //Updates
            $updates_res = $this->assistenciaModel->updatesByOperador($id);
            $acoes = [];
            if ($updates_res['erro'] == '') {
                $updates = $updates_res['updates'];
                if (empty($updates)) {
                    $dados['acoes'] = '';
                } else {

                    foreach ($updates as $up) {

                        //Data
                        $dt = new DateTime($up['updated_at']);
                        $data = $dt->format('d/m/Y');

                        //Tipo
                        $tipo = '';
                        $descricao_assistencia = $up['status_compl_updated'];
                        if ($up['status_updated'] == 'Iniciada') {

                            $tipo = '1º registro';
                            $count_Primeiro_registro += 1;

                            $primeiro_registro_res = $this->assistenciaModel->getAssistenciaById($up['id_assistencia']);
                            if ($primeiro_registro_res['erro'] == '') {
                                $descricao_assistencia = $primeiro_registro_res['assistencia']['descricao'] . ' - ' . $primeiro_registro_res['assistencia']['descricao_complemento'];
                            }
                        }
                        if ($up['status_updated'] == 'Finalizada') {

                            $tipo = 'Finalização';
                            $count_finalizacoes += 1;
                        }
                        if ($up['status_updated'] != 'Finalizada' && $up['status_updated'] != 'Iniciada') {

                            $tipo = 'Atualização';
                            $count_atualizacoes += 1;
                        }

                        $acoes[] = [
                            'id_updated' => $up['id'],
                            'id_assistencia' => $up['id_assistencia'],
                            'data' => $data,
                            'tipo' => $tipo,
                            'descricao' => $descricao_assistencia
                        ];
                    }
                }
                $dados['acoes'] = json_encode($acoes);
                $dados['num_registros'] = count($acoes);
                $dados['hoje'] = date('d/m/Y');
                $dados['count_todas'] = count($updates);
                $dados['count_primeiro_registro'] = $count_Primeiro_registro;
                $dados['count_atualizacoes'] = $count_atualizacoes;
                $dados['count_finalizacoes'] = $count_finalizacoes;
            }
        } else {
            Sessao::mensagem('user', 'ERRO ao buscar o Operador', 'alert alert-danger');
        }

        $this->view('operadores/operador', $dados);
    }


    public function user_cadastrado($id)
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        if (isset($_SESSION['dados_novo_cadastro'])) :
            return $this->view('users/create');
        endif;

        $this->view('users/user_cadastrado', $id);
    }


    public function all_operators()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        Sessao::mensagem('user', 'Operador do Sistema cadastrado com sucesso!');

        $op = $this->operadorModel->allOperators();

        if ($op['erro'] == '' && $op['operadores'] != '') {
            $dados = [
                'operadores' => json_encode($op['operadores']),
                'hoje'       => date('d/m/Y'),
                'num_registros' => count($op['operadores'])
            ];

            $this->view('operadores/index', $dados);
        } else {

            Sessao::mensagem('user', 'Falha ao listar Operadores!', 'alert alert-danger');

            $this->view('admin/index');
        }
    }

    public function usuario_page($id)
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $dados = [
            'titulo_pagina' => 'Página do usuário',
            'id' => $id
        ];

        $this->view('users/usuario_page', $dados);
    }

    public function update_pass()
    {



        // $this->view('users/pass_register', $dados);
    }




    public function meus_dados()
    {

        if (!Sessao::estaLogado()) :

            $dados = [
                'email' => '',
                'email_erro' => ''
            ];

            return $this->view('users/login_email', $dados);
        endif;

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id'               => trim($form['id']),
                'nome'             => trim($form['nome']),
                'email'            => trim($form['email']),
                'celular'          => trim($form['celular']),

                'cep'              => trim($form['cep']),
                'logradouro'       => trim($form['logradouro']),
                'numero'           => trim($form['numero']),
                'complemento'      => trim($form['complemento']),
                'bairro'           => trim($form['bairro']),
                'cidade'           => trim($form['cidade']),
                'uf'               => trim($form['uf']),
                'ibge'             => trim($form['ibge']),

                'cpf'              => trim($form['cpf']),
                'rg'               => trim($form['rg']),
                'orgao_expedidor'  => trim($form['orgao_expedidor']),
                'titulo_eleitor'   => trim($form['titulo']),
                'zona_eleitoral'   => trim($form['zona']),
                'secao_eleitoral'  => trim($form['secao']),

                'local_trabalho'   => trim($form['local_trabalho']),
                'cidade_trabalho'  => trim($form['cidade_trabalho']),
                'uf_trabalho'      => trim($form['uf_trabalho']),

                'nome_erro'        => '',
                'email_erro'       => '',
                'celular_erro'     => '',

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',

                'rg_erro'          => '',
                'cpf_erro'         => '',
                'orgao_expedidor_erro' => '',
                'titulo_erro'      => '',
                'zona_erro'        => '',
                'secao_erro'       => '',

                'local_trabalho_erro'   => '',
                'cidade_trabalho_erro'  => '',
                'uf_trabalho_erro'      => ''
            ];

            $erro = '';
            if (empty(trim($form['nome'])) || trim($form['nome']) == "") {
                $dados['nome_erro'] = 'Preencha campo nome!';
                $erro = 'erro';
            }
            if (!empty(trim($form['nome'])) && strlen($form['nome']) > 50 || strlen($form['nome']) < 2) {
                $dados['nome_erro'] = 'O nome deve conter entre 2 e 50 caracteres!';
                $erro = 'erro';
            }
            //Email
            if (empty($dados['email'])) {
                $dados['email_erro'] = 'É preciso informar o email!';
                $erro = 'erro';
            }
            if (Check::checkEmail(trim($dados['email']))) {
                $dados['email_erro'] = 'Forneça um email válido!';
                $erro = 'erro';
            }

            //verifica se email já  registrado
            if ($this->userModel->checkEmail($dados['email'])) {
                // se for registrado, verifica se é do operador, se não for...
                $compara = $this->userModel->comparaEmailId($dados['email'], $dados['id']);

                if ($compara['erro'] == '' && $compara['result'] != '') {

                    if ($compara['result'] != 1) {

                        $dados['email_erro'] = 'Este email já está registrado!';
                        $erro = 'erro';
                    }
                }
            }

            if (empty(trim($dados['celular']))) {
                $dados['celular_erro'] = 'Informe o número do celular!';
                $erro = 'erro';
            }
            if (!empty(trim($dados['celular'])) && strlen(trim($dados['celular'])) < 15) {
                $dados['celular_erro'] = 'Número incompleto';
                $erro = 'erro';
            }
            if (strlen(trim($form['cep'])) > 0 && strlen(trim($form['cep'])) < 9) {
                $dados['cep_erro'] = 'CEP incompleto!';
                $erro = 'erro';
            }
            //CPF
            if (strlen($dados['cpf']) > 0 && strlen($dados['cpf']) < 14) {
                $dados['cpf_erro'] = 'CPF incompleto.';
                $erro = 'erro';
            }
            if (strlen($dados['cpf']) == 14) {
                if (!Check::validaCPF($dados['cpf'])) {
                    $dados['cpf_erro'] = 'CPF inválido.';
                    $erro = 'erro';
                }
            }

            //Identidade
            if (strlen(trim($form['rg'])) > 0 && strlen(trim($form['rg'])) < 5) {
                $dados['rg_erro'] = 'RG incompleto!';
                $erro = 'erro';
            }
            if (strlen(trim($form['rg'])) >= 5 && strlen(trim($form['orgao_expedidor'])) == 0) {
                $dados['orgao_expedidor_erro'] = 'Informe o Orgão Expedidor!';
                $erro = 'erro';
            }

            //Titulo eleitor
            if (strlen(trim($form['titulo'])) > 0 && strlen(trim($form['titulo'])) < 5) {
                $dados['titulo_erro'] = 'Título eleitor incompleto!';
                $erro = 'erro';
            }
            //Zona eleitoral
            if (strlen(trim($form['titulo'])) > 5 && strlen(trim($form['zona'])) == 0) {
                $dados['zona_erro'] = 'Informa a zona eleitoral!';
                $erro = 'erro';
            }
            //Seção eleitoral
            if (strlen(trim($form['titulo'])) > 5 && strlen(trim($form['secao'])) == 0) {
                $dados['secao_erro'] = 'Informe a seção eleitoral!';
                $erro = 'erro';
            }
            if ($erro != '') {
                Sessao::mensagem('form_msg', 'Por favor, confira seu formulário!', 'alert alert-danger');
                $erro = 'erro';
            }
            if ($erro == '') {

                $result = $this->userModel->updateMeusDados($dados);

                if ($result['erro'] == '') {

                    $dados = [
                        'id'  => '',
                        'nome' => '',
                        'email' => '',
                        'celular' => '',

                        'cep'              => '',
                        'logradouro'       => '',
                        'numero'           => '',
                        'complemento'      => '',
                        'bairro'           => '',
                        'cidade'           => '',
                        'uf'               => '',
                        'ibge'             => '',

                        'cpf'              => '',
                        'rg'               => '',
                        'orgao_expedidor'  => '',
                        'titulo_eleitor'   => '',
                        'zona_eleitoral'   => '',
                        'secao_eleitoral'  => '',

                        'local_trabalho'   => '',
                        'cidade_trabalho'  => '',
                        'uf_trabalho'      => '',

                        'nome_erro' => '',
                        'email_erro' => '',
                        'celular_erro' => '',

                        'cep_erro'         => '',
                        'logradouro_erro'  => '',
                        'numero_erro'      => '',
                        'complemento_erro' => '',
                        'bairro_erro'      => '',
                        'cidade_erro'      => '',
                        'uf_erro'          => '',
                        'ibge_erro'        => '',
                        'rg_erro'          => '',
                        'cpf_erro'         => '',
                        'orgao_expedidor_erro' => '',
                        'titulo_erro'      => '',
                        'zona_erro'        => '',
                        'secao_erro'       => '',

                        'local_trabalho_erro'  => '',
                        'cidade_trabalho_erro' => '',
                        'uf_trabalho_erro'     => '',
                    ];

                    Sessao::mensagem('user', 'Dados alterados com sucesso!');
                } else {
                    Sessao::mensagem('user', 'ERRO ao alterar dados de Usuário, tente mais tarde!', 'alert alert-danger');
                }
            }
        } else {

            $result = $this->userModel->meusDados();

            if ($result['erro'] != '' && $result['user'] == '') {

                Sessao::mensagem('user', 'ERRO ao consultar seus dados, tente mais tarde!', 'alert alert-danger');
            } else {

                $user = $result['user'];

                $dados = [
                    'id'  => $user['id'],
                    'nome' => $user['nome'],
                    'email' => $user['email'],
                    'celular' => $user['celular'],

                    'cep'              => $user['cep'],
                    'logradouro'       => $user['logradouro'],
                    'numero'           => $user['numero'],
                    'complemento'      => $user['complemento'],
                    'bairro'           => $user['bairro'],
                    'cidade'           => $user['cidade'],
                    'uf'               => $user['uf'],
                    'ibge'             => $user['ibge'],

                    'cpf'              => $user['cpf'],
                    'rg'               => $user['rg'],
                    'orgao_expedidor'  => $user['orgao_expedidor'],
                    'titulo_eleitor'   => $user['titulo_eleitor'],
                    'zona_eleitoral'   => $user['zona_eleitoral'],
                    'secao_eleitoral'  => $user['secao_eleitoral'],

                    'local_trabalho'   => $user['local_trabalho'],
                    'cidade_trabalho'  => $user['cidade_trabalho'],
                    'uf_trabalho'      => $user['uf_trabalho'],

                    'nome_erro' => '',
                    'email_erro' => '',
                    'celular_erro' => '',

                    'cep_erro'         => '',
                    'logradouro_erro'  => '',
                    'numero_erro'      => '',
                    'complemento_erro' => '',
                    'bairro_erro'      => '',
                    'cidade_erro'      => '',
                    'uf_erro'          => '',
                    'ibge_erro'        => '',
                    'rg_erro'          => '',
                    'cpf_erro'         => '',
                    'orgao_expedidor_erro' => '',
                    'titulo_erro'      => '',
                    'zona_erro'        => '',
                    'secao_erro'       => '',

                    'local_trabalho_erro'  => '',
                    'cidade_trabalho_erro' => '',
                    'uf_trabalho_erro'     => '',
                ];
            }
        }

        $this->view('users/meus_dados', $dados);
    }

    public function sistema_suspenso($sistema) {

        $this->view('users/suspenso');
    }

    //Formulário para trocar senha
    // public function recovery_pass()
    // {

    //     $expired_link = filter_input(INPUT_GET, 'key');

    //     if ($expired_link) :
    //         if (!$this->userDao->checkKeyRecovery($expired_link)) :
    //             $dados = [
    //                 'message' => 'Link expirado!'
    //             ];

    //             Sessao::mensagem('email', 'Link invlido ou expirado! Por favor, envie uma nova solicitação! <a class="btn btn-outline-danger" href="' . URL . '/emails/password_recovery">Ok</a>', 'alert alert-danger');

    //             return $this->message_page();
    //         endif;
    //     endif;

    //     $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

    //     if (isset($form)) :

    //         $dados = [
    //             'key' => trim($form['key']),
    //             'email' => trim($form['email']),
    //             'pass' => trim($form['pass']),
    //             'confirm-pass' => trim($form['confirm-pass']),
    //             'email_erro' => '',
    //             'pass_erro' => '',
    //             'confirm-pass_erro' => '',
    //         ];

    //         if (in_array("", $form)) :

    //             if (empty($form['email'])) :
    //                 $dados['email_erro'] = 'Preencha o campo e-mail';
    //             endif;

    //             if (empty($form['pass'])) :
    //                 $dados['pass_erro'] = 'Preencha o campo senha';
    //             endif;

    //             if (empty($form['confirm-pass'])) :
    //                 $dados['confirm-pass_erro'] = 'Confirme a senha';
    //             endif;

    //         else :

    //             if (Check::checkEmail($form['email'])) :
    //                 $dados['email_erro'] = 'Forneça um e-mail válido!';
    //             elseif (!$this->userDao->checkEmail($form['email'])) :
    //                 $dados['email_erro'] = 'O e-mail informado não está registrado no sistema.';
    //             else :

    //                 if (strlen($form['pass']) < 6) :
    //                     $dados['pass_erro'] = 'A senha deve ter no mínimo 6 caracteres.';

    //                 elseif ($form['pass'] != $form['confirm-pass']) :
    //                     $dados['confirm-pass_erro'] = 'As senhas devem ser iguais.';

    //                 else :

    //                     $recovery_pass = $this->userDao->recoveryPass($dados);
    //                     if ($recovery_pass) :
    //                         Sessao::mensagem('user', 'Senha registrada com sucesso!');

    //                         $dados = [
    //                             'email' => '',
    //                             'pass' => '',
    //                             'email_erro' => '',
    //                             'pass_erro' => '',
    //                         ];

    //                         $this->view('users/login', $dados);
    //                     else :
    //                         Sessao::mensagem('email', 'Este link está inválido! Por favor, envie uma nova solicitação!', 'alert alert-danger');

    //                         $dados = [
    //                             'email' => '',
    //                             'email_erro' => '',
    //                         ];

    //                         $this->view('emails/password-recovery', $dados);
    //                     endif;

    //                 endif;

    //             endif;

    //         endif;

    //     else :

    //         $dados = [
    //             'key' => filter_input(INPUT_GET, 'key'),
    //             'email' => '',
    //             'pass' => '',
    //             'confirm-pass' => '',
    //             'email_erro' => '',
    //             'pass_erro' => '',
    //             'confirm-pass_erro' => '',
    //         ];

    //     endif;

    //     $this->view('users/recovery.pass', $dados);
    // }

    public function update_minha_senha()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $id = $_SESSION['user']['id'];

        $hash = $this->userModel->getHash($id);

        if (isset($form)) {

            $erro = '';

            $dados = [
                'id' => $id,
                'nome' => $_SESSION['user']['nome'],
                'pass' => $form['pass'],
                'confirm_pass' => $form['confirm_pass'],
                'atual_pass' => $form['atual_pass'],

                'pass_erro' => '',
                'confirm_pass_erro' => '',
                'atual_pass_erro' => ''
            ];

            if (empty($form['pass'])) {
                $dados['pass_erro'] = 'Informe a nova senha';
                $erro = 'erro';
            }

            if (strlen($form['pass']) > 0) {
                if (Check::checkSenha(trim($form['pass']))) {
                    $dados['pass_erro'] = 'A senha deve ter oito dgitos e ter pelo menos uma letra minúscula, uma letra maiúscula, 
                1 número e um caractere especial.';
                    $erro = 'erro';
                }
            }

            if (empty($form['confirm_pass'])) {
                $dados['confirm_pass_erro'] = 'Repita a nova senha aqui.';
                $erro = 'erro';
            }

            if (!empty($form['confirm_pass']) && $form['pass'] != $form['confirm_pass']) {
                $dados['confirm_pass_erro'] = 'As senhas não estão iguais!';
                $erro = 'erro';
            }

            if (empty($form['atual_pass']) || $form['atual_pass'] == '') {
                $dados['atual_pass_erro'] = 'Informe a senha atual!';
                $erro = 'erro';
            } else {
                if (!password_verify(trim($dados['atual_pass']), $hash['hash'])) {
                    $dados['atual_pass_erro'] = 'SENHA INVÁLIDA!';
                    $erro = 'erro';
                }
            }

            if ($erro == '') {

                $result = $this->userModel->updateMinhaSenha($dados);
                if ($result['affected_rows'] == 1 && $result['erro'] == '') {

                    Sessao::mensagem('msg', 'Senha alterada com sucesso, por favor, faça o login novamente com a nova senha! <a href="' . URL . '/users/logout" class="btn btn-outline-success">Ok</a>');

                    return $this->view('users/mensagem');
                } else {
                    Sessao::mensagem('user', 'ERRO ao fazer alteração da senha, tente mais tarde!', 'alert alert-danger');
                }
            }
        } else {

            $dados = [
                'nome' => $_SESSION['user']['nome'],
                'pass' => '',
                'confirm_pass' => '',
                'atual_pass' => '',

                'pass_erro' => '',
                'confirm_pass_erro' => '',
                'atual_pass_erro' => ''
            ];
        }

        $this->view('users/update_minha_senha', $dados);
    }

    public function message_page()
    {
        $this->view('pages/message-page');
    }

    public function nav_manager()
    {

        $this->view('users/nav_manager');
    }
}
