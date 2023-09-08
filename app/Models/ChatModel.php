<?php

class ChatModel
{

    private $sessao_acesso;

    private $host = 'localhost'; // DB['HOST'];
    private $username = 'assi_degiutech'; //DB['USER'];
    private $password = 'Degiutech23@'; // DB['PASS'];
    private $database = 'assi_fabinho_db'; //DB['DATABASE'];
    private $conexao = null;



    public function __construct()
    {
        // $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function insert_mesages($dados)
    {

        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $res = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO chat_msgs (id_sala, msg, id_emissor, nome_emissor, id_receptor, data_hora) 
        VALUES(?, ?, ?, ?, ?, ?)"))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_sala = $dados['id_sala'];
        $msg = $dados['msg'];
        $id_emissor = $dados['id_emissor'];
        $nome_emissor = $dados['nome_emissor'];
        $id_receptor = $dados['id_receptor'];
        $data_hora = $dados['data_hora'];


        if (!$stmt->bind_param(
            "isisis",
            $id_sala,
            $msg,
            $id_emissor,
            $nome_emissor,
            $id_receptor,
            $data_hora
        )) {
            $res['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {

            $res['id'] = mysqli_insert_id($mysqli);

            mysqli_stmt_close($stmt);
        }

        // mysqli_stmt_close($stmt);

        return $res;
    }

    public function getMesagesByIdSala($id_sala)
    {

        // $db = new Database();
        // $mysqli = $db->getConection();
        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $retorno = ['mesages' => '', 'erro' => ''];

        $query = "SELECT * FROM chat_msgs WHERE id_sala=?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id_sala);
        $stmt->execute();
        $result = $stmt->get_result();

        $retorno['mesages'] = $result->fetch_all(MYSQLI_ASSOC);

        mysqli_close($mysqli);

        return $retorno;
    }

    //Pega uma mensagem
    public function getMsgById($id_msg)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $retorno = ['msg' => '', 'erro' => ''];

        $query = "SELECT * FROM chat_msgs WHERE id=?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id_msg);
        $stmt->execute();
        $result = $stmt->get_result();
        mysqli_affected_rows($mysqli);

        $retorno['msg'] = $result->fetch_assoc();

        mysqli_close($mysqli);

        return $retorno;
    }

    public function getSala($id_sala)
    {
        // $db = new Database();
        // $mysqli = $db->getConection();
        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $retorno = ['sala' => '', 'erro' => ''];

        $query = "SELECT * FROM chat_salas WHERE id=?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id_sala);
        $stmt->execute();
        $result = $stmt->get_result();
        mysqli_affected_rows($mysqli);

        $retorno['sala'] = $result->fetch_all(MYSQLI_ASSOC);

        mysqli_close($mysqli);

        return $retorno;
    }

    public function createSala($dados)
    {

        // $db = new Database();
        // $mysqli = $db->getConection();
        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $res = ['erro' => '', 'id_sala' => ''];

        //Se não existe...
        if (!($stmt = $mysqli->prepare("INSERT INTO chat_salas (id_user1, nome_user1, id_user2, nome_user2, grupo) 
        VALUES(?, ?, ?, ?, ?)"))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_user1 = $dados['id_user1'];
        $nome_user1 = $dados['nome_user1'];
        $id_user2 = $dados['id_user2'];
        $nome_user2 = $dados['nome_user2'];
        $grupo = $dados['grupo'];

        if (!$stmt->bind_param(
            "isiss",
            $id_user1,
            $nome_user1,
            $id_user2,
            $nome_user2,
            $grupo
        )) {
            $res['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $res['id_sala'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);

        return $res;
    }

    //ID da sala onde o usuário logado tem conversa com outro usuário
    public function otherUserSala($other, $id_user)
    {
        // $db = new Database();
        // $mysqli = $db->getConection();
        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $query = "SELECT id FROM chat_salas WHERE (id_user1=? OR id_user1=?) AND (id_user2=? OR id_user2=?)";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iiii", $id_user, $other, $other, $id_user);
        $stmt->execute();
        // $result = $stmt->get_result(); // get the mysqli result
        $stmt->bind_result($id);
        $stmt->fetch();
        $retorno = $id;

        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);

        return $retorno;
    }

    //Altera o status de leitura da sala e o id do emissor
    public function updateLeituraEmissorSala($id_sala, $leitura, $id_emissor = null)
    {

        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($id_emissor) {
            $query = "UPDATE chat_salas SET leitura = '$leitura', id_emissor = '$id_emissor' WHERE id = '$id_sala'";
        } else {
            $query = "UPDATE chat_salas SET leitura = '$leitura' WHERE id = '$id_sala'";
        }

        if ($result = mysqli_query($mysqli, $query)) {
            // Return the number of rows in result set
            $retorno = true;
        } else {
            $retorno = false;
        }

        mysqli_close($mysqli);

        return $retorno;
    }

    public function statusLeituraEmissorSala($id_sala)
    {

        $mysqli = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        $query = "SELECT leitura, id_emissor FROM chat_salas WHERE id=?";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id_sala);
        $stmt->execute();
        // $result = $stmt->get_result(); // get the mysqli result
        $stmt->bind_result($leitura, $id_emissor);
        $stmt->fetch();
        $retorno = ['leitura' => $leitura, 'id_emissor' => $id_emissor];

        mysqli_stmt_close($stmt);
        mysqli_close($mysqli);

        return $retorno;
    }

    private function get_conection()
    {
        return mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );
    }
}
