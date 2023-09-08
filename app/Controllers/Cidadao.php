<?php

class Cidadao extends Controller
{

    private $home;
    private $sessao_acesso;

    private $representanteModel;
    private $coordenacaoModel;
    private $cidadaoModel;
    private $assistenciaModel;

    public function __construct()
    {

        // if (!Sessao::estaLogado()) :
        //     URL::redirect('users/login_email');
        // endif;

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

        $this->representanteModel = $this->model('RepresentanteModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
    }

    public function edit($id_cidadao)
    {

        // unset($_SESSION['dados_edit_cidadao']);

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id_cidadao' => $id_cidadao,
                'nome' => trim($form['nome']),
                'sexo' => trim($form['sexo']),
                'email' => trim($form['email']),
                'data_nasc' => trim($form['data_nasc']),
                'celular' => trim($form['celular']),
                'cpf' => trim($form['cpf']),
                'rg' => trim($form['rg']),
                'orgao_expedidor' => trim($form['orgao_expedidor']),
                'titulo' => trim($form['titulo']),
                'zona' => trim($form['zona']),
                'secao' => trim($form['secao']),
                'sus' => trim($form['sus']),
                'home'      => $this->home,

                'nome_erro' => '',
                'email_erro' => '',
                'data_nasc_erro' => '',
                'celular_erro' => '',
                'cpf_erro' => '',
                'rg_erro' => '',
                'orgao_expedidor_erro' => '',
                'titulo_erro' => '',
                'zona_erro' => '',
                'secao_erro' => '',
                'sus_erro' => ''
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

            if (!empty($dados['email'])) {
                if (Check::checkEmail(trim($dados['email']))) {
                    $dados['email_erro'] = 'Forneça um email válido!';
                    $erro = 'erro';
                }
            }
            if (!empty(trim($dados['celular'])) && strlen(trim($dados['celular'])) < 15) {
                $dados['celular_erro'] = 'Número incompleto';
                $erro = 'erro';
            }
            //Data nascimento
            $dt_atual = date('Y-m-d');
            if (!empty($dados['data_nasc'])) {
                if (strtotime($dados['data_nasc']) >= strtotime($dt_atual)) {
                    $dados['data_nasc_erro'] = 'Informe uma data válida!';
                    $erro = 'erro';
                }
            } else {
                $dados['data_nasc'] = '0000-00-00';
            }
            //CPF
            if (strlen($dados['cpf']) > 0 && strlen($dados['cpf']) < 14) {
                $dados['cpf_erro'] = 'CPF incompleto.';
                $erro = 'erro';
            }
            if (strlen($dados['cpf']) == 14) {
                if (!Check::validaCPF($dados['cpf'])) {
                    $dados['cpf_erro'] = 'CPF invlido.';
                    $erro = 'erro';
                } else {
                    $res_cpf = $this->cidadaoModel->verificaCPF($dados['cpf']);
                    if ($res_cpf['erro'] == '' && $res_cpf['id'] != '') {
                        if ($res_cpf['id'] != $dados['id_cidadao']) {
                            $dados['cpf_erro'] = 'Este CPF já está cadastrado!';
                            $erro = 'erro';
                        }
                    }
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
                $dados['titulo_erro'] = 'Ttulo eleitor incompleto!';
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
            //SUS
            //Cartão do SUS
            if (strlen(trim($form['sus'])) > 0 && strlen(trim($form['sus'])) < 18) {
                $dados['sus_erro'] = 'Número incompleto!';
                $erro = 'erro';
            }
            if ($erro == '') {

                $_SESSION['dados_edit_cidadao']['id_cidadao'] = $dados['id_cidadao'];
                $_SESSION['dados_edit_cidadao']['nome'] = $dados['nome'];
                $_SESSION['dados_edit_cidadao']['sexo'] = $dados['sexo'];
                $_SESSION['dados_edit_cidadao']['email'] = $dados['email'];
                $_SESSION['dados_edit_cidadao']['data_nasc'] = $dados['data_nasc'];
                $_SESSION['dados_edit_cidadao']['celular'] = $dados['celular'];
                $_SESSION['dados_edit_cidadao']['cpf'] = $dados['cpf'];
                $_SESSION['dados_edit_cidadao']['rg'] = $dados['rg'];
                $_SESSION['dados_edit_cidadao']['orgao_expedidor'] = $dados['orgao_expedidor'];
                $_SESSION['dados_edit_cidadao']['titulo'] = $dados['titulo'];
                $_SESSION['dados_edit_cidadao']['zona'] = $dados['zona'];
                $_SESSION['dados_edit_cidadao']['secao'] = $dados['secao'];
                $_SESSION['dados_edit_cidadao']['sus'] = $dados['sus'];

                $dados = [
                    'id_cidadao'       => $id_cidadao,
                    'nome'             => $_SESSION['dados_edit_cidadao']['nome'],
                    'sexo'             => $_SESSION['dados_edit_cidadao']['sexo'],
                    'cep'              => $_SESSION['dados_edit_cidadao']['cep'],
                    'logradouro'       => $_SESSION['dados_edit_cidadao']['logradouro'],
                    'numero'           => $_SESSION['dados_edit_cidadao']['numero'],
                    'complemento'      => $_SESSION['dados_edit_cidadao']['complemento'],
                    'bairro'           => $_SESSION['dados_edit_cidadao']['bairro'],
                    'cidade'           => $_SESSION['dados_edit_cidadao']['cidade'],
                    'uf'               => $_SESSION['dados_edit_cidadao']['uf'],
                    'ibge'             => $_SESSION['dados_edit_cidadao']['ibge'],
                    'home'      => $this->home,

                    'cep_erro'         => '',
                    'logradouro_erro'  => '',
                    'numero_erro'      => '',
                    'complemento_erro' => '',
                    'bairro_erro'      => '',
                    'cidade_erro'      => '',
                    'uf_erro'          => '',
                    'ibge_erro'        => '',
                ];

                return $this->view('cidadao/endereco_edit', $dados);
            }
        } else {
            $cidadao_res = $this->cidadaoModel->cidadao($id_cidadao);
            if ($cidadao_res['erro'] == '' && $cidadao_res['cidadao'] != '') {
                $cidadao = $cidadao_res['cidadao'];
            }

            $dados = [
                'id_cidadao' => $cidadao['id'],
                'nome'    => $cidadao['nome'],
                'sexo'    => $cidadao['sexo'],
                'email'    => $cidadao['email'],
                'celular'    => $cidadao['celular'],
                'data_nasc'    => $cidadao['data_nascimento'],
                'cep' => $cidadao['cep'],
                'logradouro' => $cidadao['logradouro'],
                'numero' => $cidadao['numero'],
                'complemento' => $cidadao['complemento'],
                'bairro' => $cidadao['bairro'],
                'cidade' => $cidadao['cidade'],
                'uf' => $cidadao['uf'],
                'ibge'   => $cidadao['ibge'],
                'cpf'    => $cidadao['cpf'],
                'rg'    => $cidadao['rg'],
                'orgao_expedidor'    => $cidadao['orgao_expedidor'],
                'titulo'     => $cidadao['titulo_eleitor'],
                'zona'     => $cidadao['zona_eleitoral'],
                'secao'    => $cidadao['secao_eleitoral'],
                'sus'    => $cidadao['sus'],
                // 'local_trabalho' => $cidadao['local_trabalho'],
                // 'cidade_trabalho' => $cidadao['cidade_trabalho'],
                // 'uf_trabalho' => $cidadao['uf_trabalho'],
                'id_updated_by' => $cidadao['id_updated_by'],
                'nome_updated_by' => $cidadao['nome_updated_by'],
                'facebook' => $cidadao['facebook'],
                'twitter' => $cidadao['twitter'],
                'instagram' => $cidadao['instagram'],
                'telegram' => $cidadao['telegram'],
                'skype' => $cidadao['skype'],
                'tiktok' => $cidadao['tiktok'],
                'home'      => $this->home,


                'nome_erro'    => '',
                'sexo_erro'    => '',
                'email_erro'    => '',
                'celular_erro'    => '',
                'data_nasc_erro'    => '',
                'cpf_erro'        => '',
                'rg_erro'         => '',
                'orgao_expedidor_erro' => '',
                'titulo_erro'     => '',
                'zona_erro'     => '',
                'secao_erro'    => '',
                'sus_erro'      => ''
            ];

            $_SESSION['dados_edit_cidadao'] = $dados;
        }

        return $this->view('cidadao/edit', $dados);
    }

    public function endereco_edit()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id_cidadao'       => $_SESSION['dados_edit_cidadao']['id_cidadao'],
                'nome'             => $_SESSION['dados_edit_cidadao']['nome'],
                'cep'              => trim($form['cep']),
                'logradouro'       => trim($form['logradouro']),
                'numero'           => trim($form['numero']),
                'complemento'      => trim($form['complemento']),
                'bairro'           => trim($form['bairro']),
                'cidade'           => trim($form['cidade']),
                'uf'               => trim($form['uf']),
                'ibge'             => trim($form['ibge']),

                //midias sociais
                'facebook' => $_SESSION['dados_edit_cidadao']['facebook'],
                'twitter' => $_SESSION['dados_edit_cidadao']['twitter'],
                'instagram' => $_SESSION['dados_edit_cidadao']['instagram'],
                'telegram' => $_SESSION['dados_edit_cidadao']['telegram'],
                'skype' => $_SESSION['dados_edit_cidadao']['skype'],
                'tiktok' => $_SESSION['dados_edit_cidadao']['tiktok'],

                'facebook_erro' => '',
                'twitter_erro' => '',
                'instagram_erro' => '',
                'telegram_erro' => '',
                'skype_erro' => '',
                'tiktok_erro' => '',

                'home'      => $this->home,

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',
            ];

            //Se não for fazer tratamento de erros;;;
            $_SESSION['dados_edit_cidadao']['cep'] = $dados['cep'];
            $_SESSION['dados_edit_cidadao']['logradouro'] = $dados['logradouro'];
            $_SESSION['dados_edit_cidadao']['numero'] = $dados['numero'];
            $_SESSION['dados_edit_cidadao']['complemento'] = $dados['complemento'];
            $_SESSION['dados_edit_cidadao']['bairro'] = $dados['bairro'];
            $_SESSION['dados_edit_cidadao']['cidade'] = $dados['cidade'];
            $_SESSION['dados_edit_cidadao']['uf'] = $dados['uf'];
            $_SESSION['dados_edit_cidadao']['ibge'] = $dados['ibge'];

            return $this->view('cidadao/midias_sociais_edit', $dados);
        } else {

            $dados = [
                // 'cidadao'          => $_SESSION['dados_cadastro_cidadao']['cidadao'],
                'nome'             => $_SESSION['dados_edit_cidadao']['nome'],
                'cep'              => $_SESSION['dados_edit_cidadao']['cep'],
                'logradouro'       => $_SESSION['dados_edit_cidadao']['logradouro'],
                'numero'           => $_SESSION['dados_edit_cidadao']['numero'],
                'complemento'      => $_SESSION['dados_edit_cidadao']['complemento'],
                'bairro'           => $_SESSION['dados_edit_cidadao']['bairro'],
                'cidade'           => $_SESSION['dados_edit_cidadao']['cidade'],
                'uf'               => $_SESSION['dados_edit_cidadao']['uf'],
                'ibge'             => $_SESSION['dados_edit_cidadao']['ibge'],
                'home'             => $this->home,

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',
                'existe'     => 'não existe'
            ];
        }

        $this->view('cidadao/endereco_edit', $dados);
    }

    public function midias_sociais_edit()
    {
        if (!isset($_SESSION['dados_edit_cidadao'])) {
            return $this->cadastros_recentes();
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados['id_cidadao'] = $_SESSION['dados_edit_cidadao']['id_cidadao'];
            $dados['nome'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['facebook'] = trim($form['facebook']);
            $dados['twitter'] = trim($form['twitter']);
            $dados['instagram'] = trim($form['instagram']);
            $dados['telegram'] = trim($form['telegram']);
            $dados['skype'] = trim($form['skype']);
            $dados['tiktok'] = trim($form['tiktok']);

            $dados['facebook_erro'] = '';
            $dados['twitter_erro'] = '';
            $dados['instagram_erro'] = '';
            $dados['telegram_erro'] = '';
            $dados['skype_erro'] = '';
            $dados['tiktok_erro'] = '';

            $_SESSION['dados_edit_cidadao']['facebook'] = $dados['facebook'];
            $_SESSION['dados_edit_cidadao']['twitter'] = $dados['twitter'];
            $_SESSION['dados_edit_cidadao']['instagram'] = $dados['instagram'];
            $_SESSION['dados_edit_cidadao']['telegram'] = $dados['telegram'];
            $_SESSION['dados_edit_cidadao']['skype'] = $dados['skype'];
            $_SESSION['dados_edit_cidadao']['tiktok'] = $dados['tiktok'];


            if (isset($_SESSION['dados_edit_cidadao']) && $_SESSION['dados_edit_cidadao'] != '') {

                // Atualiza os dados
                $edit = $this->cidadaoModel->edit($_SESSION['dados_edit_cidadao']);

                if ($edit['erro'] == '' && $edit['affected_rows'] != 0) {
                    // SUCESSO
                    Sessao::mensagem('cidadao', 'Atualização de cadastro de Cidadão efetuado com sucesso!');

                    $id_cidadao = $_SESSION['dados_edit_cidadao']['id_cidadao'];

                    // apaga a sessão
                    $_SESSION['dados_edit_cidadao'] = '';
                    unset($_SESSION['dados_edit_cidadao']);

                    return $this->cidadao($id_cidadao);
                } else {
                    //ERRO
                    Sessao::mensagem('cidadao', 'ERRO ao cadastrar Cidadão, tente mais tarde!', 'alert alert-danger');
                    return $this->cadastros_recentes();
                }
            }
        } else {
            $dados['id_cidadao'] = $_SESSION['dados_edit_cidadao']['id_cidadao'];
            $dados['nome'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['facebook'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['twitter'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['instagram'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['telegram'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['skype'] = $_SESSION['dados_edit_cidadao']['nome'];
            $dados['tiktok'] = $_SESSION['dados_edit_cidadao']['nome'];

            $dados['facebook_erro'] = '';
            $dados['twitter_erro'] = '';
            $dados['instagram_erro'] = '';
            $dados['telegram_erro'] = '';
            $dados['skype_erro'] = '';
            $dados['tiktok_erro'] = '';
        }

        $this->view('cidadao/midias_sociais_edit', $dados);
    }

    //FUNÇÕES PARA NOVO CIDADÃO

    public function create($retornar = null)
    {

        if (!$retornar) {
            unset($_SESSION['dados_cadastro_cidadao']);
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            if (empty($form)) {
                return $this->cadastros_recentes();
            }

            $dados = [
                'nome' => trim($form['nome']),
                'sexo' => trim($form['sexo']),
                'email' => trim($form['email']),
                'data_nasc' => trim($form['data_nasc']),
                'celular' => trim($form['celular']),
                'cpf' => trim($form['cpf']),
                'rg' => trim($form['rg']),
                'orgao_expedidor' => trim($form['orgao_expedidor']),
                'titulo' => trim($form['titulo']),
                'zona' => trim($form['zona']),
                'secao' => trim($form['secao']),
                'sus'     => trim($form['sus'])
            ];

            $dados['home'] = $this->home;
            $dados['nome_erro'] = '';
            $dados['email_erro'] = '';
            $dados['data_nasc_erro'] = '';
            $dados['celular_erro'] = '';
            $dados['cpf_erro'] = '';
            $dados['rg_erro'] = '';
            $dados['orgao_expedidor_erro'] = '';
            $dados['titulo_erro'] = '';
            $dados['zona_erro'] = '';
            $dados['secao_erro'] = '';
            $dados['sus_erro'] = '';

            $erro = '';
            if (empty(trim($form['nome'])) || trim($form['nome']) == "") {
                $dados['nome_erro'] = 'Preencha campo nome!';
                $erro = 'erro';
            }
            if (!empty(trim($form['nome'])) && strlen($form['nome']) > 50 || strlen($form['nome']) < 2) {
                $dados['nome_erro'] = 'O nome deve conter entre 2 e 50 caracteres!';
                $erro = 'erro';
            }

            if (!empty($dados['email'])) {
                if (Check::checkEmail(trim($dados['email']))) {
                    $dados['email_erro'] = 'Forneça um email válido!';
                    $erro = 'erro';
                }
            }
            if (!empty(trim($dados['celular'])) && strlen(trim($dados['celular'])) < 15) {
                $dados['celular_erro'] = 'Número incompleto';
                $erro = 'erro';
            }
            //Data nascimento
            $dt_atual = date('Y-m-d');
            if (!empty($dados['data_nasc'])) {
                if (strtotime($dados['data_nasc']) >= strtotime($dt_atual)) {
                    $dados['data_nasc_erro'] = 'Informe uma data válida!';
                    $erro = 'erro';
                }
            } else {
                $dados['data_nasc'] = '0000-00-00';
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
                } else {
                    $res_cpf = $this->cidadaoModel->verificaCPF($dados['cpf']);
                    if ($res_cpf['erro'] == '' && $res_cpf['id'] != '') {
                        $dados['cpf_erro'] = 'Este CPF já está cadastrado!';
                        $erro = 'erro';
                    }
                }
            }

            //Identidade
            if (strlen(trim($form['rg'])) > 0 && strlen(trim($form['rg'])) < 5) {
                $dados['rg_erro'] = 'RG incompleto!';
                $erro = 'erro';
            }
            if (strlen(trim($form['rg'])) >= 5 && strlen(trim($form['orgao_expedidor'])) == 0) {
                $dados['orgao_expedidor_erro'] = 'Informe o Orgo Expedidor!';
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
            //SUS
            //Cartão do SUS
            if (strlen(trim($form['sus'])) > 0 && strlen(trim($form['sus'])) < 18) {
                $dados['sus_erro'] = 'Número incompleto!';
                $erro = 'erro';
            }
            if ($erro == '') {

                $_SESSION['dados_cadastro_cidadao'] = $dados;

                $dados = [
                    'nome'             => $_SESSION['dados_cadastro_cidadao']['nome'],
                    'sexo'             => 'Não declarado',
                    'cep'              => '',
                    'logradouro'       => '',
                    'numero'           => '',
                    'complemento'      => '',
                    'bairro'           => '',
                    'cidade'           => '',
                    'uf'               => '',
                    'ibge'             => '',
                    'home'             => $this->home,

                    'cep_erro'         => '',
                    'logradouro_erro'  => '',
                    'numero_erro'      => '',
                    'complemento_erro' => '',
                    'bairro_erro'      => '',
                    'cidade_erro'      => '',
                    'uf_erro'          => '',
                    'ibge_erro'        => '',
                ];

                return $this->view('cidadao/endereco', $dados);
            }
        } else {
            $dados = [
                'nome' => '',
                'sexo' => 'Não declarado',
                'email' => '',
                'data_nasc' => '',
                'celular' => '',
                'cpf' => '',
                'rg' => '',
                'orgao_expedidor' => '',
                'titulo' => '',
                'zona' => '',
                'secao' => '',
                'sus'       => '',
                'home'      => $this->home,

                'nome_erro' => '',
                'email_erro' => '',
                'data_nasc_erro' => '',
                'celular_erro' => '',
                'cpf_erro' => '',
                'rg_erro' => '',
                'orgao_expedidor_erro' => '',
                'titulo_erro' => '',
                'zona_erro' => '',
                'secao_erro' => '',
                'sus_erro'     => ''
            ];
        }

        if (isset($_SESSION['dados_cadastro_cidadao'])) {

            $dados['nome'] = $_SESSION['dados_cadastro_cidadao']['nome'];
            $dados['sexo'] = $_SESSION['dados_cadastro_cidadao']['sexo'];
            $dados['email'] = $_SESSION['dados_cadastro_cidadao']['email'];
            $dados['data_nasc'] = $_SESSION['dados_cadastro_cidadao']['data_nasc'];
            $dados['celular'] = $_SESSION['dados_cadastro_cidadao']['celular'];
            $dados['cpf'] = $_SESSION['dados_cadastro_cidadao']['cpf'];
            $dados['rg'] = $_SESSION['dados_cadastro_cidadao']['rg'];
            $dados['orgao_expedidor'] = $_SESSION['dados_cadastro_cidadao']['orgao_expedidor'];
            $dados['titulo'] = $_SESSION['dados_cadastro_cidadao']['titulo'];
            $dados['zona'] = $_SESSION['dados_cadastro_cidadao']['zona'];
            $dados['secao'] = $_SESSION['dados_cadastro_cidadao']['secao'];
            $dados['sus'] = $_SESSION['dados_cadastro_cidadao']['sus'];
        }

        $this->view('cidadao/create', $dados);
    }

    public function endereco()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (!isset($_SESSION['dados_cadastro_cidadao']) || empty($_SESSION['dados_cadastro_cidadao'])) {
            return $this->cadastros_recentes();
        }

        if (isset($form)) {

            if (empty($form)) {
                return $this->cadastros_recentes();
            }

            $dados = [
                'nome'             => $_SESSION['dados_cadastro_cidadao']['nome'],
                'cep'              => trim($form['cep']),
                'logradouro'       => trim($form['logradouro']),
                'numero'           => trim($form['numero']),
                'complemento'      => trim($form['complemento']),
                'bairro'           => trim($form['bairro']),
                'cidade'           => trim($form['cidade']),
                'uf'               => trim($form['uf']),
                'ibge'             => trim($form['ibge']),

                //midias sociais
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'telegram' => '',
                'skype' => '',
                'tiktok' => '',

                'facebook_erro' => '',
                'twitter_erro' => '',
                'instagram_erro' => '',
                'telegram_erro' => '',
                'skype_erro' => '',
                'tiktok_erro' => '',

                'home'      => $this->home,

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',
            ];

            //Se não for fazer tratamento de erros;;;
            $_SESSION['dados_cadastro_cidadao']['cep'] = $dados['cep'];
            $_SESSION['dados_cadastro_cidadao']['logradouro'] = $dados['logradouro'];
            $_SESSION['dados_cadastro_cidadao']['numero'] = $dados['numero'];
            $_SESSION['dados_cadastro_cidadao']['complemento'] = $dados['complemento'];
            $_SESSION['dados_cadastro_cidadao']['bairro'] = $dados['bairro'];
            $_SESSION['dados_cadastro_cidadao']['cidade'] = $dados['cidade'];
            $_SESSION['dados_cadastro_cidadao']['uf'] = $dados['uf'];
            $_SESSION['dados_cadastro_cidadao']['ibge'] = $dados['ibge'];

            // return $this->view('cidadao/midias_sociais', $dados);

            //PULAR MÍDIAS SOCIAIS
            $_SESSION['dados_cadastro_cidadao']['facebook'] = '';
            $_SESSION['dados_cadastro_cidadao']['twitter'] = '';
            $_SESSION['dados_cadastro_cidadao']['instagram'] = '';
            $_SESSION['dados_cadastro_cidadao']['telegram'] = '';
            $_SESSION['dados_cadastro_cidadao']['skype'] = '';
            $_SESSION['dados_cadastro_cidadao']['tiktok'] = '';

            $dados = $_SESSION['dados_cadastro_cidadao'];

            //Cadastrar Cidadão
            $create = $this->cidadaoModel->create($dados);
            $_SESSION['dados_cadastro_cidadao'] = '';
            unset($_SESSION['dados_cadastro_cidadao']);

            if ($create['erro'] == '' && $create['id'] != '') {
                Sessao::mensagem('cidadao', 'Cadastro de Cidadão efetuado com sucesso!');

                return $this->cidadao($create['id']);
            } else {
                Sessao::mensagem('cidadao', 'ERRO ao cadastrar Cidadão, tente mais tarde!', 'alert alert-danger');
                return $this->view('cidadao/create');
            }
        } else {

            $dados = [
                'nome'             => $_SESSION['dados_cadastro_cidadao']['nome'],
                'cep'              => '',
                'logradouro'       => '',
                'numero'           => '',
                'complemento'      => '',
                'bairro'           => '',
                'cidade'           => '',
                'uf'               => '',
                'ibge'             => '',
                'home'             => $this->home,

                'cep_erro'         => '',
                'logradouro_erro'  => '',
                'numero_erro'      => '',
                'complemento_erro' => '',
                'bairro_erro'      => '',
                'cidade_erro'      => '',
                'uf_erro'          => '',
                'ibge_erro'        => '',

            ];
        }

        $this->view('cidadao/endereco', $dados);
    }

    public function midias_sociais($id = null)
    {

        if (isset($_SESSION['dados_cadastro_cidadao']) && $_SESSION['dados_cadastro_cidadao'] != '') {

            $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            if (isset($form)) {

                $dados = $_SESSION['dados_cadastro_cidadao'];
                $dados['nome'] = $_SESSION['dados_cadastro_cidadao']['nome'];
                $dados['facebook'] = trim($form['facebook']);
                $dados['twitter'] = trim($form['twitter']);
                $dados['instagram'] = trim($form['instagram']);
                $dados['telegram'] = trim($form['telegram']);
                $dados['skype'] = trim($form['skype']);
                $dados['tiktok'] = trim($form['tiktok']);

                $dados['facebook_erro'] = '';
                $dados['twitter_erro'] = '';
                $dados['instagram_erro'] = '';
                $dados['telegram_erro'] = '';
                $dados['skype_erro'] = '';
                $dados['tiktok_erro'] = '';


                $create = $this->cidadaoModel->create($dados);
                $_SESSION['dados_cadastro_cidadao'] = '';
                unset($_SESSION['dados_cadastro_cidadao']);

                if ($create['erro'] == '' && $create['id'] != '') {
                    Sessao::mensagem('cidadao', 'Cadastro de Cidadão efetuado com sucesso!');

                    return $this->cidadao($create['id']);
                } else {
                    Sessao::mensagem('cidadao', 'ERRO ao cadastrar Cidadão, tente mais tarde!', 'alert alert-danger');
                    return $this->view('cidadao/create');
                }
            } else {
                $dados['nome'] = $_SESSION['dados_cadastro_cidadao']['nome'];
                $dados['facebook'] = '';
                $dados['twitter'] = '';
                $dados['instagram'] = '';
                $dados['telegram'] = '';
                $dados['skype'] = '';
                $dados['tiktok'] = '';

                $dados['facebook_erro'] = '';
                $dados['twitter_erro'] = '';
                $dados['instagram_erro'] = '';
                $dados['telegram_erro'] = '';
                $dados['skype_erro'] = '';
                $dados['tiktok_erro'] = '';
            }

            $this->view('cidadao/midias_sociais', $dados);
        } else {
            $this->cadastros_recentes();
        }
    }

    public function cidadao($id = null)
    {

        //Cidadao
        $cidadao = $this->cidadaoModel->cidadao($id);

        $msg_assistencias = '';

        //Todas as Assistências do Cidadão
        $assistencias = [];
        $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadao($id);
        if (count($assistencias_res['assistencias']) == 0) {
            $msg_assistencias = 'Cidadão sem registro de Assistências!';
        }

        //Assistencias por Coordenadoria
        if ($_SESSION['user']['id_coordenadoria'] != 0) {
            $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadaoCoordenadoria($id, $_SESSION['user']['id_coordenadoria']);
            if (count($assistencias_res['assistencias']) == 0) {
                $msg_assistencias = 'Cidadão sem registro de Assistências!';
            }
        }

        //Assistências por Representante
        if ($_SESSION['user']['acesso'] == 'Representante') {
            $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadaoRepresentante($id, $_SESSION['user']['id']);
            if (count($assistencias_res['assistencias']) == 0) {
                $msg_assistencias = 'Cidadão sem registro de Assistncias!';
            }
        }

        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != null) {

            //Assistências - array
            foreach ($assistencias_res['assistencias'] as $as) {

                // data da assistência
                $dt = new DateTime($as['date_at']);
                $data = $dt->format('d/m/Y');

                // status atual
                $status_atual = $as['status_assist'];

                //Assistencias_update - array
                $updates = [];
                $assist_up_res = $this->assistenciaModel->getAssistenciasUpdate($as['id']);

                if ($assist_up_res['erro'] == '' && $assist_up_res['assist_up'] != '') {

                    // Status atual
                    $status_atual = 'Sem atualizações'; //'Inalterada';
                    if (count($assist_up_res['assist_up']) > 1) {
                        $status_atual = $assist_up_res['assist_up'][0]['status_updated'];
                    }

                    //Descrição do statusl atual
                    $desc_status_atual = ' --- ';

                    // Data da update atual
                    $dt_js = $assist_up_res['assist_up'][0]['updated_at'];
                    $dt_up = new DateTime($dt_js);
                    $data_format = $dt_up->format('d/m/Y');
                    $data_up = ' --- ';

                    if ($data_format != $data && $assist_up_res['assist_up'][0]['status_compl_updated']) {
                        $data_up = $data_format;
                        $desc_status_atual = $assist_up_res['assist_up'][0]['status_compl_updated'];
                        $status_atual = $assist_up_res['assist_up'][0]['status_updated'];
                    }
                    // $data = $dt_up->format('d/m/Y');

                    //Updates
                    foreach ($assist_up_res['assist_up'] as $up) {

                        $d_up = new DateTime($up['updated_at']);
                        $data_up = $d_up->format('d/m/Y H:i:s');

                        $updates[] = [
                            'id' => $up['id'],
                            'id_assistencia' => $up['id_assistencia'],
                            'status_compl_updated' => $up['status_compl_updated'],
                            'name_updated_by' => $up['name_updated_by'],
                            'status_updated'  => $up['status_updated'],
                            'data'            => $data_up
                        ];
                    }
                }

                // array de assistências
                $assistencias[] = [
                    'id' => $as['id'],
                    'data' => $data,
                    'descricao' => $as['descricao'],
                    'complemento' => ' - ' . $as['descricao_complemento'],
                    'status_atual' => $status_atual,
                    'ultima_atualizacao' => $data_up,
                    'desc_status_atual' => $desc_status_atual,
                    'updates' => $updates,
                    'nome_coordenadoria' => $as['nome_coordenadoria'],
                    'id_coordenadoria'   => $as['id_coordenadoria'],
                    'desc_juridica'   => $as['desc_juridica'],
                    'num_proc_juridica'   => $as['num_proc_juridica']
                ];
            }

            // $dados['assistencias'] = json_encode($assistencias);


        }

        if ($cidadao['erro'] != '') {
            Sessao::mensagem('ERRO ao buscar dados de Cidadão!');
        } else {
            $dados = [
                'cidadao' => $cidadao['cidadao'],
                'assistencias' => $assistencias,
                'hoje' => date('d/m/Y'),
                'num_registros' => '',
                'msg_assistencias' => $msg_assistencias,
                'home'      => $this->home,
                'whatsapp'  => '',
                'idade' => Times::idade_anos($cidadao['cidadao']['data_nascimento']),
                'cpf'       => $cidadao['cidadao']['cpf'],
                'sus'       => $cidadao['cidadao']['sus'],
            ];
        }

        // Whatsapp
        if (!empty($cidadao['cidadao']['celular'])) {
            $num = $cidadao['cidadao']['celular'];
            $sem_parenteses1 = str_replace('(', '', $num);
            $sem_parenteses2 = str_replace(')', '', $sem_parenteses1);
            $sem_espaco = str_replace(' ', '', $sem_parenteses2);
            $whatsapp = str_replace('-', '', $sem_espaco);
            $dados['whatsapp'] = $whatsapp;
        }

        $this->view('cidadao/cidadao', $dados);
    }

    public function cancelar_cadastro_cidadao()
    {

        unset($_SESSION['dados_cadastro_cidadao']);

        Sessao::mensagem('cidadao', 'Cadastro de Cidadão CANCELADO pelo Operador!', 'alert alert-warning');

        $dados = [
            'nome' => '',
            'sexo' => 'Não declarado',
            'email' => '',
            'celular' => '',
            'data_nasc' => '',
            'cpf' => '',
            'rg' => '',
            'orgao_expedidor' => '',
            'titulo' => '',
            'zona' => '',
            'secao' => '',
            'home' => $this->home,

            'nome_erro' => '',
            'email_erro' => '',
            'celular_erro' => '',
            'data_nasc_erro' => '',
            'cpf_erro' => '',
            'rg_erro' => '',
            'orgao_expedidor_erro' => '',
            'titulo_erro' => '',
            'zona_erro' => '',
            'secao_erro' => ''
        ];

        $this->view('cidadao/create', $dados);
    }

    public function cancelar_edicao_cidadao($id_cidadao)
    {

        unset($_SESSION['dados_edit_cidadao']);

        Sessao::mensagem('cidadao', 'Atualização de cadastro de Cidadão CANCELADA pelo Operador!', 'alert alert-warning');

        $this->cidadao($id_cidadao);
    }

    /**
     * Últimos 50 cadastros de Cidadão
     */
    public function cadastros_recentes()
    {

        $res = $this->cidadaoModel->cadastrosRecentes();

        if ($res['erro'] == '' && $res['cidadaos'] != '') {
            $dados = [
                'cidadaos' => $res['cidadaos'],
            ];
        }

        $dados['home'] = $this->home;

        $this->view('cidadao/cadastros_recentes', $dados);
    }
}
