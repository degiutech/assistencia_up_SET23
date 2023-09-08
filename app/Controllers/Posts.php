<?php

class Posts extends Controller
{

    public function __construct()
    {
        // if (!Sessao::estaLogado()):
        //     URL::redirect('users/login_email');
        // endif;

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');

    }

    public function index()
    {
        $dados = [
            'posts' => $this->postModel->readPosts(),
        ];

        $this->view('posts/index', $dados);
    }

    public function create()
    {

        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($form)):
            $dados = [
                'titulo' => trim($form['titulo']),
                'texto' => trim($form['texto']),
                'user_id' => $_SESSION['user_id'],
                'titulo_erro' => '',
                'texto_erro' => '',
            ];

            if (in_array("", $form)):
                if (empty($form['titulo'])):
                    $dados['titulo_erro'] = 'Preencha o campo título';
                endif;

                if (empty($form['texto'])):
                    $dados['texto_erro'] = 'Preencha o campo texto';
                endif;

            else:

                if ($this->postModel->insert($dados)):
                    Sessao::mensagem('post', 'Registro de Postagem realizado com sucesso!');
                    URL::redirect('posts');
                else:
                    die("Erro ao registrar postagem no banco de dados");
                endif;

            endif;

        else:
            $dados = [
                'titulo' => '',
                'texto' => '',
                'titulo_erro' => '',
                'texto_erro' => '',
            ];
        endif;

        $this->view('posts/create', $dados);
    }

    public function edit($id)
    {

        $form = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($form)):
            $dados = [
                'id' => $id,
                'titulo' => trim($form['titulo']),
                'texto' => trim($form['texto']),
                'titulo_erro' => '',
                'texto_erro' => '',
            ];

            if (in_array("", $form)):
                if (empty($form['titulo'])):
                    $dados['titulo_erro'] = 'Preencha o campo título';
                endif;

                if (empty($form['texto'])):
                    $dados['texto_erro'] = 'Preencha o campo texto';
                endif;

            else:

                if ($this->postModel->update($dados)):
                    Sessao::mensagem('post', 'Post atualizado com sucesso!');
                    URL::redirect('posts');
                else:
                    die("Erro ao atualizar postagem no banco de dados");
                endif;

            endif;

        else:

            $post = $this->postModel->readPostById($id);

            if ($post->id_user != $_SESSION['user_id']):
                Sessao::mensagem('post', 'Você não tem aautorização para editar este post!', 'alert alert-danger');
                URL::redirect('posts');
            endif;

            $dados = [
                'id' => $post->id,
                'titulo' => $post->title_post,
                'texto' => $post->text_post,
                'titulo_erro' => '',
                'texto_erro' => '',
            ];
        endif;

        $this->view('posts/edit', $dados);
    }

    public function show($id)
    {
        $post = $this->postModel->readPostById($id);

        if ($post == null): 
            Url::redirect('pages/error');
        endif;

        $user = $this->userModel->readUserById($post->id_user);

        $dados = [
            'post' => $post,
            'user' => $user,
        ];

        $this->view('posts/show', $dados);
    }

    public function delete($id)
    {

        if ($this->checkAuthorization($id)):
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $metodo = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

            if ($id && $metodo == 'POST'):
                if ($this->postModel->delete($id)):
                    Sessao::mensagem('post', 'Post excluído com sucesso!');
                    URL::redirect('posts');
                endif;
            else:
                Sessao::mensagem('post', 'Você não tem autorização para ecluir este Post!', 'alert alert-danger');
                URL::redirect('posts');
            endif;
        else:
            Sessao::mensagem('post', 'Você não tem autorização para ecluir este Post!', 'alert alert-danger');
            URL::redirect('posts');
        endif;
    }

    public function checkAuthorization($id)
    {
        $post = $this->postModel->readPostById($id);

        if ($post->id_user != $_SESSION['user_id']):
            return false; 
        else:
            return true;
        endif;
    }

}
