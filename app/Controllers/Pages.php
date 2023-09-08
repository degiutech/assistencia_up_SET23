<?php

class Pages extends Controller
{

    public function index()
    {

        $dados = [
            'tituloPagina' => 'Página Inicial',
            'descricao' => 'Gestão de Pessoas',
            'email' => '',
            'email_erro' => ''
        ];

        if (!Sessao::estaLogado()) :
            // Url::redirect('pages/home', $dados);
            // Url::redirect('pages/home', $dados);
            // Url::redirect('users/login_email');

        endif;

        // $this->view('pages/home', $dados);
        //    $this->view('pages/inicial');
        // $this->view('users/login');
    }

    public function login_email()
    {

        $dados = [
            'email' => '',
            'email_erro' => ''
        ];

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) :

            if (empty(trim($form['email']))) :
                $dados['email_erro'] = 'Campo email não pode ser vazio!';
                return $this->view('users/login_email', $dados);

            elseif (!empty(trim($form['email'])) && Check::checkEmail(trim($form['email']))) :
                $dados['email_erro'] = 'Digite um email válido!';
                return $this->view('users/login_email', $dados);

            endif;

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

    public function home()
    {
        $dados = [
            'tituloPagina' => 'Página HOME',
            'descricao' => 'PORTAL ANDRÉ - HOME'
        ];

        // if (Sessao::estaLogado()):
        //     Url::redirect('pages/home', $dados);
        // endif;

        $this->view('pages/home', $dados);
    }

    public function sobre()
    {
        $dados = [
            'tituloPagina' => 'Página Sobre'
        ];

        $this->view('pages/sobre', $dados);
    }

    public function uploadimg()
    {


        $this->view('pages/upload-img');
    }

    public function error()
    {
        $dados = [
            'tituloPagina' => 'Erro - Página não encontrada'
        ];

        $this->view('pages/error', $dados);
    }

    public function inicial()
    {
        $dados = [
            'tituloPagina' => 'Inicial - testando'
        ];

        $this->view('pages/inicial', $dados);
    }

    public function inicial2()
    {
        $dados = [
            'tituloPagina' => 'Inicial - testando'
        ];

        $this->view('pages/inicial2', $dados);
    }


    public function message_page()
    {
        $dados = [
            'tituloPagina' => 'Página de mensagens',
            'message' => 'mensagem'
        ];

        $this->view('pages/message-page', $dados);
    }
}
