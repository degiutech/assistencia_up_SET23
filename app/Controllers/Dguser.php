<?php

class DgUser extends Controller
{

    private $sessao_user = null;
    private $sessao_acesso;
    private $home;

    public function __construct()
    {

        if (SessaoDg::estaLogado()) :
            $this->sessao_user = $_SESSION['user'];
        // URL::redirect('users/login_email');$this->sessao_acesso = SessaoDg::sessaoUser();

        endif;

        $this->userModel = $this->model('UserModel');
        $this->representanteModel = $this->model('RepresentanteModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
        $this->cidadaoModel = $this->model('CidadaoModel');

        $this->dgUserModel = $this->model('DgUserModel');
    }

    public function create()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {
            $dados = [
                'nome' => trim($form['nome']),
                'email' => trim($form['email']),
                'celular' => trim($form['celular']),
                'pass'         => trim($form['pass']),
                'confirm_pass' => trim($form['confirm_pass']),

                'nome_erro' => '',
                'email_erro' => '',
                'celular_erro' => '',
                'pass_erro'    => '',
                'confirm_pass_erro' => ''
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
            if ($this->dgUserModel->checkEmail(trim($dados['email']))) {
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
            if (strlen($form['pass']) < 6) :
                $dados['pass_erro'] = 'A senha deve ter no mínimo 6 caracteres.';
                $erro = 'erro';
            endif;

            if ($form['pass'] != $form['confirm_pass']) :
                $dados['confirm_pass_erro'] = 'As senhas devem ser iguais.';
                $erro = 'erro';
            endif;
            if ($erro == '') {

                $create = $this->dgUserModel->create($dados);
                if ($create['erro'] == '') {
                    SessaoDg::mensagem('dg_user', 'Usuário DegiuTech cadastrado com sucesso! <a href="' . URL . '/dguser/login_dg" class="btn btn-success">OK</a>');
                } else {
                    SessaoDg::mensagem('dg_user', 'ERRO ao cadastrar usuário DegiuTech!', 'alert alert-danger');
                }
            }
        }

        $dados = [
            'nome' => '',
            'email' => '',
            'celular' => '',
            'pass'         => '',
            'confirm_pass' => '',

            'nome_erro' => '',
            'email_erro' => '',
            'celular_erro' => '',
            'pass_erro'    => '',
            'confirm_pass_erro' => ''
        ];

        $this->view('degiutech/dg_user_create', $dados);
    }

    public function login_dg()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :

            $dados = [
                'email' => trim($form['email']),
                'pass' => trim($form['pass']),
                'email_erro' => '',
                'pass_erro' => '',
            ];

            $erro = '';

            // email
            if (empty(trim($form['email']))) :
                $dados['email_erro'] = 'Campo email não pode ser vazio!';
                $erro = 'erro';

            elseif (!empty(trim($form['email'])) && Check::checkEmail(trim($form['email']))) :
                $dados['email_erro'] = 'Digite um email válido!';
                $erro = 'erro';

            endif;

            // senha
            if (empty($form['pass'])) :
                $dados['pass_erro'] = 'Preencha o campo senha';
                $erro = 'erro';
            endif;

            if ($erro == '') :
                $login = $this->dgUserModel->login($dados);
                if ($login['msg'] == '') {
                    $user = $this->dgUserModel->getUser($login['id']);
                    if ($user['erro'] == '') {
                        return $this->create_session($user['user']);
                    } else {
                        SessaoDg::mensagem('dg_user', $user['msg'], 'alert alert-danger');
                    }
                } else {
                    SessaoDg::mensagem('dg_user', $login['msg'], 'alert alert-danger');
                }

            endif;

        else :
            $dados = [
                'email' => '',
                'pass' => '',
                'email_erro' => '',
                'pass_erro' => '',
            ];
        endif;


        $this->view('degiutech/login_dg', $dados);
    }

    public function create_session($user)
    {

        unset($_SESSION['user']);

        $_SESSION['user'] = [];
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['nome'] = $user['nome'];
        $_SESSION['user']['celular'] = $user['celular'];
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['user']['acesso'] = $user['acesso'];
        $_SESSION['user']['app'] = 'degiutech';

        if ($_SESSION['user']['acesso'] == 'Admin0') {
            Url::redirect('degiutech/admin0');
        }
    }

    public function logof()
    {
        unset($_SESSION['user']);

        Url::redirect('dguser/login_dg');
    }

    public function meus_dados()
    {

        if (!$this->sessao_user) {
            Url::redirect('users/login_email');
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id' => trim($form['id']),
                'nome' => trim($form['nome']),
                'celular' => trim($form['celular']),
                'email' => trim($form['email'])
            ];
        } else {

            $id_user = $this->sessao_user['id'];
            $user_res = $this->dgUserModel->getUser($id_user);
            if ($user_res['erro'] == '' && $user_res['msg'] == '') {
                $user = $user_res['user'];
            }

            $dados = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'celular' => $user['celular'],
                'email' => $user['email']
            ];
        }

        $this->view('degiutech/meus_dados', $dados);
    }

    public function trocar_senha()
    {

        if (!$this->sessao_user) {
            Url::redirect('users/login_email');
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $hash = $this->dgUserModel->getHash($this->sessao_user['id']);

            $dados = [
                'id' => $this->sessao_user['id'],
                'senha_atual' => trim($form['senha_atual']),
                'pass' => trim($form['pass']),
                'confirm_pass' => trim($form['confirm_pass']),

                'senha_atual_erro' => '',
                'pass_erro' => '',
                'confirm_pass_erro'
            ];

            $erro = '';

            // senha atual
            if (empty($form['senha_atual']) || $form['senha_atual'] == '') {
                $dados['senha_atual_erro'] = 'Informe a senha atual!';
                $erro = 'erro';
            } else {
                if (!password_verify(trim($dados['senha_atual']), $hash['hash'])) {
                    $dados['senha_atual_erro'] = 'SENHA INVÁLIDA!';
                    $erro = 'erro';
                }
            }

            // Pass
            if (empty($form['pass'])) {
                $dados['pass_erro'] = 'Informe a nova senha';
                $erro = 'erro';
            }

            if (strlen($form['pass']) > 0) {
                if (Check::checkSenha(trim($form['pass']))) {
                    $dados['pass_erro'] = 'A senha deve ter oito dígitos e ter pelo menos uma letra minúscula, uma letra maiúscula, 
    1 número e um caractere especial.';
                    $dados['confirm_pass_erro'] = '';
                    $erro = 'erro';
                }
            }

            //confirmar senha
            if (empty($form['confirm_pass'])) {
                $dados['confirm_pass_erro'] = 'Repita a nova senha aqui.';
                $erro = 'erro';
            }

            if (!empty($form['confirm_pass']) && $form['pass'] != $form['confirm_pass']) {
                $dados['confirm_pass_erro'] = 'As senhas não estão iguais!';
                $erro = 'erro';
            }

            if ($form['confirm_pass'] == $form['pass']) {
                $dados['confirm_pass_erro'] = '';
            }



            if ($erro == '') {

                $result = $this->dgUserModel->updateSenha($dados);
                if ($result['affected_rows'] == 1 && $result['erro'] == '') {

                    Sessao::mensagem('msg', 'Senha alterada com sucesso, por favor, faça o login novamente com a nova senha!');
                    // URL::redirect('dguser/login_dg');
                    return $this->logof();
                } else {
                    Sessao::mensagem('msg', 'ERRO ao fazer alteração da senha, tente mais tarde!', 'alert alert-danger');

                    $dados = [
                        'id' => $this->sessao_user['id'],
                        'senha_atual' => '',
                        'pass' => '',
                        'confirm_pass' => '',

                        'senha_atual_erro' => '',
                        'pass_erro' => '',
                        'confirm_pass_erro' => ''
                    ];

                    return $this->view('degiutech/trocar_senha', $dados);
                }
            }
        } else {

            $dados = [
                'id' => $this->sessao_user['id'],
                'senha_atual' => '',
                'pass' => '',
                'confirm_pass' => '',

                'senha_atual_erro' => '',
                'pass_erro' => '',
                'confirm_pass_erro' => ''
            ];
        }

        $this->view('degiutech/trocar_senha', $dados);
    }
}
