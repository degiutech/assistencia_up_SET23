<?php

class Controller
{

    public function __construct()
    {
        require_once __DIR__ . '/../Helpers/Url.php';
        if (!Sessao::estaLogado()) {
            Url::redirect('users/login_email');
        }
    }

    public function model($model)
    {
        require_once '../app/Models/' . $model . '.php';
        return new $model;
    }

    public function dao($dao)
    {
        require_once '../app/Dao/' . $dao . '.php';
        return new $dao;
    }

    public function controller($controller)
    {
        require_once '../app/Controllers/' . $controller . '.php';
        return new $controller;
    }

    public function view($view, $dados = [])
    {
        $arquivo = ('../app/Views/' . $view . '.php');
        if (file_exists($arquivo)) :
            require_once $arquivo;
        else :
            die('O arquivo de view não existe!');
        endif;
    }
}
