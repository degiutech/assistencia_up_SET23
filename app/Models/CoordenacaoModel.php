<?php

class CoordenacaoModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function allCoordenadorias()
    {
        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'coordenadorias' => ''];
        $coord = [];

        $query = "SELECT * FROM coordenadorias ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {
            // $res['operadores'] = $result->fetch_all();
            while ($row = $result->fetch_assoc()) {
                $coord[] = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'created_by' => $row['created_by'],
                    'created_at' => $row['created_at']
                ];
            }
        }

        $res['coordenadorias'] = $coord;

        $db->connClose();

        return $res;
    }

    public function novaCoordenadoria($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $nome = $dados['nome'];
        $created_by = $_SESSION['user']['nome'];

        $existe = $mysqli->query("SELECT nome FROM coordenadorias WHERE nome = '$nome'");
        if ($existe->num_rows > 0) {
            return 'Esta Coordenadoria já existe!';
        }

        $result = ['id' => '', 'erro' => ''];

        $query = "INSERT INTO coordenadorias (nome, created_by) VALUES(?, ?)";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $nome, $created_by)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['id'] = mysqli_insert_id($mysqli);


        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function editCoordenadoria($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['erro' => '', 'num_rows' => ''];

        $query = "UPDATE coordenadorias SET nome=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $id   = $dados['id'];

        if (!$stmt->bind_param("si", $nome, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['num_rows'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    /**
     * Dados de uma Coordenadoria
     */
    public function getDadosCoordenadoria($id_coordenadoria)
    {

        $dados = [
            'erro' => '',
            'coordenadoria' => ''
        ];

        // Coordenadoria
        $coordenadoria_res = $this->getCoordenadoria($id_coordenadoria);
        if ($coordenadoria_res['erro'] == '' && $coordenadoria_res['coordenadoria'] != '') {
            $dados['coordenadoria'] = $coordenadoria_res['coordenadoria'];
        } else {
            $dados['erro'] = $coordenadoria_res['erro'];
            return $dados;
        }

        //Coordenadores
        $coordenadores_res = $this->getCoordenadoresByCoordenadoria($id_coordenadoria);
        if ($coordenadores_res['erro'] == '' && $coordenadores_res['coordenadores'] != '') {
            $dados['coordenadores'] = $coordenadores_res['coordenadores'];
        } else {
            $dados['erro'] = $coordenadores_res['erro'];
            return $dados;
        }

        //Assistências de uma Coordenadoria
        // $ass_model = new AssistenciaModel;
        // $assistencias_res = $ass_model->AssistenciasRecentesByCoordenadoria($id_coordenadoria);
        // if ($assistencias_res['erro'] == '') {
        //     $dados['assistencias'] = $assistencias_res['assistencias'];
        // }

        return $dados;
    }

    /**
     * Coordenadoria
     */
    public function getCoordenadoria($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'coordenadoria' => ''];

        $query = "SELECT * FROM coordenadorias WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result
        $res['coordenadoria'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Nome de Coordenadoria
     */
    public function getNomeCoordenadoria($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'nome' => ''];

        $query = "SELECT nome FROM coordenadorias WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$result = $stmt->get_result()) {
            $res['erro'] = "Result failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $res['nome'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Coordenadores de uma Coordenadoria
     */
    public function getCoordenadoresByCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'coordenadores' => ''];
        $coord = [];

        $query = "SELECT id, nome FROM users WHERE id_coordenadoria=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_coordenadoria)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result

        while ($row = $result->fetch_assoc()) {
            $coord[] = $row;
        }

        $res['coordenadores'] = $coord;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Todos os Coordenadores
     */
    public function getCoordenadoresByAcesso()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'coordenadores' => ''];

        $acesso = 'Coordenadoria';

        $query = "SELECT * FROM users WHERE acesso=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $acesso)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result

        while ($row = $result->fetch_assoc()) {
            $res['coordenadores'] = [
                'id' => $row['id'],
                'nome' => $row['nome']
            ];
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function allCoordenadores()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'coordenadores' => ''];
        $coordenadores = [];

        $query = "SELECT * FROM users WHERE acesso='Coordenadoria' ORDER BY updated_at DESC";

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

                $nome_coordenadoria = '';
                if ($row['id_coordenadoria'] != 0) {
                    $nome_coordenadoria_res = $this->getNomeCoordenadoria($row['id_coordenadoria']);
                    $nome_coordenadoria = $nome_coordenadoria_res['nome'];
                }

                $coordenadores[] = [
                    'id' => $row['id'],
                    'nome' => $row['nome'],
                    'email' => $row['email'],
                    'celular' => $row['celular'],
                    'acesso' => $row['acesso'],
                    'id_coordenadoria' => $row['id_coordenadoria'],
                    'nome_coordenadoria' => $nome_coordenadoria['nome'],
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

        $res['coordenadores'] = $coordenadores;

        $db->connClose();

        return $res;
    }

    // CONTADORES
    //Coordenadorias
    public function countCoordenadorias()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM coordenadorias";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    // Coordenadores
    public function countCoordenadores()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM users WHERE acesso = 'Coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    // FIM CONTADORES

    // POR COORDENADORIA




    //operadores por assistencias_update.id_coordenadoria
    public function operadoresByIdAssistenciaUpdate($id_coordenadoria)
    {
    }
}
