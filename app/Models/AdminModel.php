<?php

class AdminModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    //Contador de Administradores
    public function countAllAdministradores() {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM users WHERE acesso = 'Administração'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;

    }
}
