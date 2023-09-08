<?php

class SupervisaoModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function allSupervisores()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'supervisores' => ''];
        $supers = [];

        $query = "SELECT * FROM users WHERE acesso='Supervisao' ORDER BY updated_at DESC";

        // if ($this->sessao_acesso['acesso'] == 'Supervisão') {
        //     $query = "SELECT * FROM users WHERE acesso!='Dev' AND acesso!='Administração' AND acesso !='Supervisão' ORDER BY created_at DESC";
        // }
        // if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
        //     $query = "SELECT * FROM users WHERE acesso='Representante' ORDER BY created_at DESC";
        // }

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {
            // $res['operadores'] = $result->fetch_all();
            while ($row = $result->fetch_assoc()) {
                $supers[] = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'email' => $row['email'],
                    'celular' => $row['celular'],
                    'acesso' => $row['acesso'],
                    'bloqueio' => $row['bloqueio'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'cep' => $row['cep'],
                    'logradouro' => $row['logradouro'],
                    'numero' => $row['numero'],
                    'bairro' => $row['bairro'],
                    'cidade' => $row['cidade'],
                    'uf' => $row['uf'],
                    'ibge' => $row['ibge']
                ];
            }
        }

        $res['supervisores'] = $supers;

        $db->connClose();

        return $res;
    }

    //Contador de Supervisores
    public function countAllSupervisores()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM users WHERE acesso = 'Supervisao'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }
}
