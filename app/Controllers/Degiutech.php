<?php

class Degiutech extends Controller
{

    private $dg_user;

    public function __construct()
    {

        if (!SessaoDg::estaLogado()) {
            // URL::redirect('dguser/login_dg');
        }

        $this->dg_user = $_SESSION['user'];

        $this->faqModel = $this->model('FaqModel');
    }

    public function admin0()
    {

        $dados = [
            'nome_dg_user' => $this->dg_user['nome']
        ];

        $this->view('degiutech/admin0/index', $dados);
    }

    public function faqs()
    {

        $nao_resp_res = $this->faqModel->naoRespondidasGeral();

        $dados = [
            'perguntas' => $nao_resp_res['perguntas'],
        ];

        $this->view('degiutech/faqs/index', $dados);
    }

    public function responder()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id' => trim($form['id']),
                'resposta' => trim($form['resposta']),
            ];

            $res_res = $this->faqModel->responder($dados);
            if ($res_res['erro'] == '' && $res_res['affected_rows'] == 1) {
                SessaoDg::mensagem('msg', 'Resposta enviada com sucesso!');
            }
            if ($res_res['erro'] != '' || $res_res['affected_rows'] == 0) {
                SessaoDg::mensagem('msg', 'ERRO ao registrar Resposta!', 'alert alert-danger');
            }
        }

        $this->faqs();
    }
}
