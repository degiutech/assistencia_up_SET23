<?php

class Faq extends Controller
{

    private $sessao_acesso;
    private $home;

    public function __construct()
    {

        if (!Sessao::estaLogado()) :
            // URL::redirect('users/login_email');
        else :
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
        endif;

        $this->userModel = $this->model('UserModel');
        $this->faqModel = $this->model('FaqModel');
    }

    public function faq()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form) && !empty($form['pergunta'])) {
            $pergunta = trim($form['pergunta']);

            $create = $this->faqModel->create($pergunta);

            if ($create['erro'] == '') {
                Sessao::mensagem('faq', 'Sua pergunta foi registrada com sucesso, responderemos o mais rápido possível.');
            } else {
                Sessao::mensagem('faq', 'ERRO: ' . $create['erro'], 'alert alert-danger');
            }
        }

        $acesso = $_SESSION['user']['acesso'];

        $perguntas_res = $this->faqModel->perguntas($acesso);
        if ($perguntas_res['erro'] == '') {
            $dados = [
                'perguntas' => $perguntas_res['perguntas']
            ];
        }

        $dados['home'] = $this->home;

        $this->view('faq/faq', $dados);
    }
}
