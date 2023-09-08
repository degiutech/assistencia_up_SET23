<?php

class Emails extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
        $this->userDao = $this->dao('UserDao');
        $this->emailDao = $this->dao('EmailDao');
    }

    //Formulário para enviar e-mail para recuperação de senha
    public function password_recovery()
    {

        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($form)):

            $dados = [
                'email' => trim($form['email']),
                'email_erro' => '',
            ];

            if (in_array("", $form)):

                if (empty($form['email'])):
                    $dados['email_erro'] = 'Preencha o campo e-mail';
                endif;

            else:
                if (Check::checkEmail($dados['email'])):
                    $dados['email_erro'] = 'O e-mail informado é inválido';
                else:

                    $email = $dados['email'];

                    $user = $this->userDao->getUserByEmail($email);

                    if (!$user):
                        Sessao::mensagem('email', 'O e-mail informado não está registrado no sistema.', 'alert alert-danger');
                    else:

                        $recovery = $this->userDao->saveRecovery($user);

                        if ($recovery):

                            $dados_email = [
                                'from_email' => APP_EMAIL,
                                'from_name' => APP_EMAIL_NAME,
                                'recipient_name' => $user->name_user,
                                'recipient_email' => $form['email'],
                                'subject' => 'Recuperação e/ou registro de senha',
                                'message_html' => '<p><h4>Você solicitou a recuperação e/ou registro de sua senha em nossa base de dados.</p></h4><a href="' . $recovery . '">Acesse este link para completar a ação.</a><p><b>Atenção: este link vai expirar em 24 horas a contar do horário de sua solicitação.</b></p><p><h6>Se não foi você quem solicitou, por favor, desconsidere esta mensagem.</h6></p>',
                                'message' => "Você solicitou a recuperação e/pu registro de sua senha em nossa base de dados. Copie a url abaixo e cole no seu navegador para completar a ação.\n \n" . $recovery . "\n \n Atenção: este link vai expirar em 24 horas a contar do horário de sua solicitação.\n \n Se não foi você quem solicitou, por favor, desconsidere esta mensagem."
                            ];

                            $send = $this->emailDao->sendEmailApp($dados_email);

                            if ($send):

                                $dados = [
                                    'email' => '',
                                    'email_erro' => '',
                                    'pass' => '',
                                    'pass_erro' => '',
                                ];

                                Sessao::mensagem('email', 'O sistema enviou para <b>' . $email . '</b> um link para a recuperação e/ou registro de sua senha que vai expirar em 24 horas. <a href="' . URL . '" class="btn btn-success">OK</a>');

                                $this->view('users/login', $dados);
                            else:
                                Sessao::mensagem('email', 'Erro ao enviar email. Por favor, tente mais tarde.', 'alert alert-danger');
                            endif;

                        else:
                            Sessao::mensagem('email', 'Erro ao enviar email. Por favor, tente novamente.', 'alert alert-danger');
                        endif;

                    endif;

                endif;

            endif;

        else:

            $dados = [
                'email' => '',
                'email_erro' => '',
            ];

        endif;

        $this->view('emails/password-recovery', $dados);
    }

    public function hold_page($dados)
    {
        // var_dump($hold);
        $this->view('emails/hold-page', $dados);
    }

}
