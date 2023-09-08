<?php

class RepresentanteModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function allRepresentantes()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'representantes' => ''];
        $representantes = [];

        $query = "SELECT * FROM users WHERE acesso='Representante' ORDER BY updated_at DESC";

        // if ($this->sessao_acesso['acesso'] == 'Supervisão') {
        //     $query = "SELECT * FROM users WHERE acesso!='Dev' AND acesso!='Administração' AND acesso !='Supervisão' ORDER BY created_at DESC";
        // }
        // if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
        //     $query = "SELECT * FROM users WHERE acesso='Representante' ORDER BY created_at DESC";
        // }

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {

                $representantes[] = [
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
                    'ibge' => $row['ibge'],
                    'local_trabalho' => $row['local_trabalho'],
                    'cidade_uf_trabalho' => $row['cidade_trabalho'] . ' - ' . $row['uf_trabalho']
                ];
            }
        }

        $res['representantes'] = $representantes;

        $db->connClose();

        return $res;
    }

    //Contador de Representantes
    public function countAllRepresentantes()
    {
        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM users WHERE acesso = 'Representante'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }
}
