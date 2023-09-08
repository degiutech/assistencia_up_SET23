<?php

/**
 * [ SESSAO ] - Classe auxiliar para prover alert de mensagens e verificar se está logdo e outros
 * 
 * @autor Marcos Antonio Galvão <marcosgalvaotecnologia@gmail.com>
 * @copyright 2021-2021 MAG-TEC
 */

class Sessao
{

    public static function mensagem($nome, $texto = null, $classe = null)
    {
        $nome = json_encode($nome);

        if (!empty($nome)) :
            if (!empty($texto) && empty($_SESSION[$nome])) :
                if (!empty($_SESSION[$nome])) :
                    unset($_SESSION[$nome]);
                endif;

                $_SESSION[$nome] = $texto;
                $_SESSION[$nome . 'classe'] = $classe;

            elseif (!empty($_SESSION[$nome]) && empty($texto)) :
                $classe = !empty($_SESSION[$nome . 'classe']) ? $_SESSION[$nome . 'classe'] : 'alert alert-success';
                echo '<div class="' . $classe . '">' . $_SESSION[$nome] . '</div>';

                unset($_SESSION[$nome]);
                unset($_SESSION[$nome . 'classe']);
            endif;

        endif;
    }

    public static function estaLogado()
    {
        if (isset($_SESSION['user'])) :
            return true;
        else :
            return false;
        endif;
    }

    public static function sessaoUser()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
    }
}
