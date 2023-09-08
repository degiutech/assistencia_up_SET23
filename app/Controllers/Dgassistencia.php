<?php

class Dgassistencia extends Controller
{

    private $dg_user;
    private $faqModel;
    private $operadorModel;
    private $dgAssiModel;
    private $userModel;

    public function __construct()
    {

        if (!SessaoDg::estaLogado()) {
            // URL::redirect('dguser/login_dg');
        }

        $this->dg_user = $_SESSION['user'];
        $this->faqModel = $this->model('FaqModel');
        $this->operadorModel = $this->model('OperadorModel');
        $this->userModel = $this->model('UserModel');
        $this->dgAssiModel = $this->model('DgAssistenciaModel');
    }


    public function index()
    {

        $this->view('degiutech/assistencia/index');
    }

    public function create_operador()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {
            
            $dados = [
                'nome' => trim($form['nome']),
                'email' => trim($form['email']),
                'pass' => trim($form['pass']),
                'pass_confirm' => trim($form['pass_confirm']),
                'acesso' => 'Representante',
                'bloqueio' => 'Desbloqueado',

                'email_erro' => '',
                'pass_erro' => '',
                'pass_confirm_erro' => ''
            ];

            $erro = '';

            if ($this->userModel->checkEmail(trim($dados['email']))) {
                $dados['email_erro'] = 'Este email já está registrado!';
                $erro = 'erro';
            }

            if ($dados['pass'] != $dados['pass_confirm']) {
                $dados['pass_confirm_erro'] = 'As senhas não são iguais!';
                $erro = 'erro';
            }

            if ($erro == '') {
                $create = $this->dgAssiModel->createOperador($dados);
                if ($create['erro'] == '') {
                    $dados = [
                        'id_operador' => $create['id'],
                        'id_updated_by' => $_SESSION['user']['id'],
                        'name_updated_by' => $_SESSION['user']['nome'] . ' - Degiutech',
                        'updated_what'    => 'Cadastro'
                    ];

                    $user_update = $this->operadorModel->usersUpdateActions($dados);

                    if ($user_update['erro_users_update'] == '' && $user_update['affected_rows_users_update'] == 1) {

                        SessaoDg::mensagem('assistencia', 'Operador de Assistência registrado com sucesso!');
                        return $this->operadores();
                    }
                } else {
                    SessaoDg::mensagem('assistencia', 'ERRO ao registrar Operador!', 'alert alert-danger');
                }
            }
            
        } else {

            $dados = [
                'nome' => '',
                'email' => '',
                'pass' => '',
                'pass_confirm' => '',

                'nome_erro' => '',
                'email_erro' => '',
                'pass_erro' => '',
                'pass_confirm_erro' => ''
            ];
        }

        $this->view('degiutech/assistencia/create_operador', $dados);
    }

    public function update_operador($id) {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {
            
            $dados = [
                'id' => $id,
                'nome' => trim($form['nome']),
                'email' => trim($form['email']),
                'email_original' => trim($form['email_original']),
                'pass' => trim($form['pass']),
                'pass_confirm' => trim($form['pass_confirm']),

                'email_erro' => '',
                'pass_erro' => '',
                'pass_confirm_erro' => ''
            ];

            $erro = '';

            if (trim($dados['email']) != trim(($dados['email_original']))) {
                if ($this->userModel->checkEmail(trim($dados['email']))) {
                    $dados['email_erro'] = 'Este email já está registrado!';
                    $erro = 'erro';
                }
            }

            if ($dados['pass'] != $dados['pass_confirm']) {
                $dados['pass_confirm_erro'] = 'As senhas não são iguais!';
                $erro = 'erro';
            }

            if ($erro == '') {
                $create = $this->dgAssiModel->updateOperador($dados);
                if ($create['erro'] == '') {
                    SessaoDg::mensagem('assistencia', 'Operador de Assistência registrado com sucesso!');
                    $dados = [
                        'id' => '',
                        'nome' => '',
                        'email' => '',
                        'pass' => '',
                        'pass_confirm' => '',
                        'email_original' => '',
        
                        'nome_erro' => '',
                        'email_erro' => '',
                        'pass_erro' => '',
                        'pass_confirm_erro' => ''
                    ];
                    return $this->operadores();
                } else {
                    SessaoDg::mensagem('assistencia', 'ERRO ao registrar Operador!', 'alert alert-danger');
                }
            }
            
        } else {

            $op_res = $this->operadorModel->getInfoOperator($id);

            if ($op_res['erro'] == '') {
                $op = $op_res['operador'];
            }

            $dados = [
                'id' => $op['id'],
                'nome' => $op['nome'],
                'email' => $op['email'],
                'email_original' => $op['email'],
                'pass' => '',
                'pass_confirm' => '',

                'nome_erro' => '',
                'email_erro' => '',
                'pass_erro' => '',
                'pass_confirm_erro' => ''
            ];
        }

        $this->view('degiutech/assistencia/update_operador', $dados);

    }

    public function operadores() {
        $rep_res = $this->operadorModel->allOperators();
        if ($rep_res['erro'] == '' && $rep_res['operadores'] != '') {
            $operadores = $rep_res['operadores'];
        }

        $dados = [
            'operadores' => json_encode($operadores),
            'num_registros' => count($operadores),
            'hoje'          => date('d/m/Y')
        ];

        $this->view('degiutech/assistencia/operadores', $dados);
    }
}
