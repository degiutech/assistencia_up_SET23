<?php

class OperadorModel
{

    private $sessao_acesso;

    public function __construct()
    {

        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function allOperators()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'operadores' => ''];
        $op = [];

        $query = "SELECT * FROM users WHERE acesso != 'Dev' ORDER BY updated_at DESC";

        if ($this->sessao_acesso['acesso'] == 'Supervisão') {
            $query = "SELECT * FROM users WHERE acesso != 'Dev' AND acesso!='Administração' AND acesso !='Supervisão' ORDER BY updated_at DESC";
        }
        if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
            $query = "SELECT * FROM users WHERE acesso='Representante' ORDER BY updated_at DESC";
        }

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {
            // $res['operadores'] = $result->fetch_all();
            while ($row = $result->fetch_assoc()) {

                $op[] = $row;
            }
        }

        $res['operadores'] = $op;

        $db->connClose();

        return $res;
    }

    public function getInfoOperator($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'operador' => ''];

        $query = "SELECT * FROM users WHERE id=? AND acesso!='Dev'";

        // if ($this->sessao_acesso['acesso'] == 'Supervisão') {
        //     $query = "SELECT * FROM users WHERE id=? AND acesso!='Dev' AND acesso!='Administração' AND acesso !='Supervisão'";
        // }
        // if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
        //     $query = "SELECT * FROM users WHERE id-? AND acesso='Representante' AND acesso!='Dev'";
        // }

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
        $res['operador'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }


    /**
     * Altera acesso Admin ou Supervisor e Bloqueio
     */
    public function updateAdminSuperConfig($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => '', 'id' => ''];

        $query = "UPDATE users SET acesso=?, id_coordenadoria=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $acesso = $dados['acesso'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $id = $dados['id'];


        if (!$stmt->bind_param("sii", $acesso, $id_coordenadoria, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);
        $result['id'] = $id;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    /**
     * 
     */
    public function updateCoordenadorConfig($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => '', 'id' => ''];

        $query = "UPDATE users SET acesso=?, id_coordenadoria=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $acesso = $dados['acesso'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $id = $dados['id'];


        if (!$stmt->bind_param("sii", $acesso, $id_coordenadoria, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);
        $result['id'] = $id;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function updateRepresentanteConfig($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => '', 'id' => ''];

        $query = "UPDATE users SET acesso=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $acesso = $dados['acesso'];
        $id = $dados['id'];


        if (!$stmt->bind_param("si", $acesso, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);
        $result['id'] = $id;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function bloqueioDesbloqueio($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => '', 'id' => '', 'bloqueio' => ''];

        $query = "UPDATE users SET bloqueio=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $bloqueio = '';

        if ($dados['bloqueio'] == 'Bloqueado') {
            $bloqueio = 'Desbloqueado';
        }
        if ($dados['bloqueio'] == 'Desbloqueado') {
            $bloqueio = 'Bloqueado';
        }

        $id = $dados['id'];


        if (!$stmt->bind_param("si", $bloqueio, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);
        $result['id'] = $id;
        $result['bloqueio'] = $bloqueio;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function updateOperador($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE users SET nome=?, email=?, celular=?, cep=?, logradouro=?, numero=?, complemento=?, 
        bairro=?, cidade=?, uf=?, ibge=?, cpf=?, rg=?, orgao_expedidor=?, titulo_eleitor=?, zona_eleitoral=?, 
        secao_eleitoral=?, local_trabalho=?, cidade_trabalho=?, uf_trabalho=?, id_updated_by=?, nome_updated_by=?, updated_at=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $email = $dados['email'];
        $celular = $dados['celular'];
        $cep = $dados['cep'];
        $logradouro = $dados['logradouro'];
        $numero = $dados['numero'];
        $complemento = $dados['complemento'];
        $bairro = $dados['bairro'];
        $cidade = $dados['cidade'];
        $uf = $dados['uf'];
        $ibge = $dados['ibge'];
        $cpf = $dados['cpf'];
        $rg = $dados['rg'];
        $orgao_expedidor = $dados['orgao_expedidor'];
        $titulo_eleitor = $dados['titulo_eleitor'];
        $zona_eleitoral = $dados['zona_eleitoral'];
        $secao_eleitoral = $dados['secao_eleitoral'];
        $local_trabalho = $dados['local_trabalho'];
        $cidade_trabalho = $dados['cidade_trabalho'];
        $uf_trabalho = $dados['uf_trabalho'];
        $id_updated_by = $_SESSION['user']['id'];
        $nome_updated_by = $_SESSION['user']['nome'];
        $updated_at = date('Y-m-d H:i:s');
        $id = $dados['id'];


        if (!$stmt->bind_param(
            "ssssssssssssssssssssissi",
            $nome,
            $email,
            $celular,
            $cep,
            $logradouro,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $uf,
            $ibge,
            $cpf,
            $rg,
            $orgao_expedidor,
            $titulo_eleitor,
            $zona_eleitoral,
            $secao_eleitoral,
            $local_trabalho,
            $cidade_trabalho,
            $uf_trabalho,
            $id_updated_by,
            $nome_updated_by,
            $updated_at,
            $id
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function updatePassOperador($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows_update' => '', 'erro_update' => '', 'affected_rows_users_update' => '', 'erro_users_update' => ''];

        $query = "UPDATE users SET senha=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro_update'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $senha = password_hash($dados['pass'], PASSWORD_DEFAULT);
        $id = $dados['id_operador'];


        if (!$stmt->bind_param("si", $senha, $id)) {
            $result['erro_update'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro_update'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows_update'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        if ($result['affected_rows_update'] == 1 && $result['erro_update'] == '') {
            $users_update = $this->usersUpdateActions($dados);
            $result['affected_rows_users_update'] = $users_update['affected_rows_users_update'];
            $result['erro_users_update'] = $users_update['erro_users_update'];
        }

        return $result;
    }

    /**
     * Registra todas as ações
     */
    public function usersUpdateActions($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows_users_update' => '', 'erro_users_update' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO users_update (id_user, id_updated_by, name_updated_by, updated_what) 
        VALUES(?, ?, ?, ?)"))) {
            $result['erro_users_update'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_user = $dados['id_operador'];
        $id_updated_by = $dados['id_updated_by'];
        $name_updated_by = $dados['name_updated_by'];
        $updated_what = $dados['updated_what'];



        if (!$stmt->bind_param(
            "iiss",
            $id_user,
            $id_updated_by,
            $name_updated_by,
            $updated_what
        )) {
            $result['erro_users_update'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro_users_update'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['affected_rows_users_update'] = mysqli_affected_rows($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    //CONTADORES
    public function countAllOperadores()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM users WHERE acesso != 'Dev'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //Busca todos operadores por nome
    public function buscaOperadorByNome($texto, $id_coordenadoria)
    {

        $res = ['num_rows' => 0, 'res' => []];

        $conn = new Database();
        $mysqli = $conn->getConection();

        $query = "SELECT id_updated_by, name_updated_by FROM assistencias_update WHERE id_coordenadoria='$id_coordenadoria' AND UPPER(name_updated_by) LIKE '%$texto%' LIMIT 15";

        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $res['res'][] = $row;
            }
            $res['num_rows'] = count($res['res']);
        }

        mysqli_free_result($result);
        $mysqli->close();

        return json_encode($res);
    }
}
