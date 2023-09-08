<?php

class Operadores extends Controller
{

    private $sessao;
    private $sessao_acesso;
    private $view_index;

    public function __construct()
    {
        if (!Sessao::estaLogado()) :
            // URL::redirect('users/login_email');
        endif;

        $this->sessao = Sessao::sessaoUser();
        $this->sessao_acesso = $this->sessao['acesso'];

        if ($this->sessao['acesso'] == 'Administração') {
            $this->view_index = 'admin';
        }
        if ($this->sessao['acesso'] == 'Supervisão') {
            $this->view_index = 'supervisao/index';
        }
        if ($this->sessao['acesso'] == 'Coordenadoria') {
            $this->view_index = 'coordenacao';
        }
        if ($this->sessao['acesso'] == 'Representante') {
            $this->view_index = 'representante';
        }

        $this->operadorModel = $this->model('OperadorModel');
        $this->userModel = $this->model('UserModel');
    }

    public function index()
    {

        $op = $this->operadorModel->allOperators();

        $dados = [
            'operadores' => json_encode($op['operadores']),
            'hoje'       => date('d/m/Y'),
            'num_registros' => count($op['operadores']),
            'home'          => $this->view_index
        ];

        $this->view('operadores/index', $dados);
    }

    public function edit_operador($id = null)
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [   
                'bloqueio'         => trim($form['bloqueio']), 
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

            //verifica se email já é registrado
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

                $result = $this->operadorModel->updateOperador($dados);

                if ($result['erro'] == '') {
                    Sessao::mensagem('msg', 'Dados de Operador alterados com sucesso! <a href="' . URL . '/operadores/index" class="btn btn-outline-success">Ok</a>');
                } else {
                    Sessao::mensagem('msg', 'ERRO ao alterar dados de Operador, tente mais tarde! <a href="' . URL . '/operadores/index" class="btn btn-outline-success">Ok</a>', 'alert alert-danger');
                }
                return $this->view('operadores/mensagem');
            }
        } else {

            $operador = $this->operadorModel->getInfoOperator($id);

            if ($operador['operador']['acesso'] == 'Administração' && $this->sessao_acesso != 'Administração') {

                Sessao::mensagem('user', 'Acesso negado!', 'alert alert-danger');
                return $this->view($this->view_index);
            } elseif ($operador['erro'] != '' && $operador['operador'] == '') {

                Sessao::mensagem('user', 'ERRO ao consultar Operador, tente mais tarde!', 'alert alert-danger');
                return $this->view($this->view_index);
            } else {

                $op = $operador['operador'];

                $dados = [
                    'bloqueio' => $op['bloqueio'],
                    'id'  => $op['id'],
                    'nome' => $op['nome'],
                    'email' => $op['email'],
                    'celular' => $op['celular'],

                    'cep'              => $op['cep'],
                    'logradouro'       => $op['logradouro'],
                    'numero'           => $op['numero'],
                    'complemento'      => $op['complemento'],
                    'bairro'           => $op['bairro'],
                    'cidade'           => $op['cidade'],
                    'uf'               => $op['uf'],
                    'ibge'             => $op['ibge'],

                    'cpf'              => $op['cpf'],
                    'rg'               => $op['rg'],
                    'orgao_expedidor'  => $op['orgao_expedidor'],
                    'titulo_eleitor'   => $op['titulo_eleitor'],
                    'zona_eleitoral'   => $op['zona_eleitoral'],
                    'secao_eleitoral'  => $op['secao_eleitoral'],

                    'local_trabalho'   => $op['local_trabalho'],
                    'cidade_trabalho'  => $op['cidade_trabalho'],
                    'uf_trabalho'      => $op['uf_trabalho'],

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

        $this->view('operadores/edit_operador', $dados);
    }

    public function update_pass_op($id)
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id_operador' => trim($form['id_operador']),
                'nome_operador' => trim($form['nome_operador']),
                'id_updated_by' => $_SESSION['user']['id'],
                'name_updated_by' => $_SESSION['user']['nome'],
                'pass' => trim($form['pass']),
                'confirm_pass' => trim($form['confirm_pass']),
                'updated_what' => 'Troca de senha',

                'pass_erro' => '',
                'confirm_pass_erro' => '',
            ];

            $erro = '';

            if (strlen(trim($form['pass'])) < 6 || empty(trim($form['pass']))) {
                $dados['pass_erro'] = 'A senha deve ter pelo menos 6 dígitos';
                $erro = 'erro';
            }

            if (empty($form['confirm_pass'])) {
                $dados['confirm_pass_erro'] = 'Repita a nova senha aqui.';
                $erro = 'erro';
            }

            if (!empty($form['confirm_pass']) && $form['pass'] != $form['confirm_pass']) {
                $dados['confirm_pass_erro'] = 'As senhas não estão iguais!';
                $erro = 'erro';
            }

            if ($erro == '') {

                $result = $this->operadorModel->updatePassOperador($dados);
                
                if ($result['affected_rows_update'] == 1 && $result['erro_update'] == '') {

                    Sessao::mensagem('msg', 'Senha de Operador alterada com sucesso! <a href="' . URL . '/operadores/index" class="btn btn-outline-success">Ok</a>');
                }
                if ($result['erro_update'] != '') {
                    Sessao::mensagem('msg', 'ERRO ao fazer alteração da senha, tente mais tarde! <a href="' . URL . '/operadores/index" class="btn btn-outline-danger">Ok</a>', 'alert alert-danger');
                }
                return $this->view('operadores/mensagem');
            }
        } else {

            $operador = $this->operadorModel->getInfoOperator($id);

            if ($operador['operador']['acesso'] == 'Administração' && $this->sessao_acesso != 'Administração') {

                Sessao::mensagem('user', 'Acesso negado!', 'alert alert-danger');
                return $this->view($this->view_index);
            }
            if ($operador['operador']['acesso'] == 'Supervisão') {

                if ($this->sessao_acesso == 'Coordenadoria' || $this->sessao_acesso == 'Representante') {
                    Sessao::mensagem('user', 'Acesso negado!', 'alert alert-danger');
                    return $this->view($this->view_index);
                }
            }
            if ($operador['erro'] != '' && $operador['operador'] == '') {

                Sessao::mensagem('user', 'ERRO ao consultar Operador, tente mais tarde!', 'alert alert-danger');
                return $this->view($this->view_index);
            }

            $dados = [
                'id_operador' => $operador['operador']['id'],
                'nome_operador' => $operador['operador']['nome'],
                'pass' => '',
                'confirm_pass' => '',

                'pass_erro' => '',
                'confirm_pass_erro' => ''
            ];
        }
        $this->view('operadores/update_pass_op', $dados);
    }



    // public function mensagem()
    // {

    //     $this->view('operadores/mensagem');
    // }
}
