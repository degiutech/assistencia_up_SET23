<?php

class DgUserModel
{

    public function __construct()
    {
    }



    /**
     * Registra um usuário
     * 
     */
    public function create($dados)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $result = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO dg_users (nome, email, celular, pass) 
        VALUES(?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $email = $dados['email'];
        $celular = $dados['celular'];
        $pass = password_hash($dados['pass'], PASSWORD_DEFAULT);


        if (!$stmt->bind_param(
            "ssss",
            $nome,
            $email,
            $celular,
            $pass
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if ($result['erro'] == '') {
            $result['id'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    /**
     * Função para checar se existe email
     * 
     * return boolean
     */
    public function checkEmail($email)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        if (!($stmt = $mysqli->prepare("SELECT email FROM dg_users WHERE email = ?"))) {
            return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $email)) {
            return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            //return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) > 0) {
                mysqli_stmt_close($stmt);
                $db->connClose();
                return true;
            } else {
                mysqli_stmt_close($stmt);
                $db->connClose();
                return false;
            }
        }
    }

    public function login($dados)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $resposta = ['id' => '', 'erro' => '', 'msg' => ''];

        if (!($stmt = $mysqli->prepare("SELECT pass, id FROM dg_users WHERE email = ?"))) {
            $resposta['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $email = $dados['email'];
        $pass = $dados['pass'];

        if (!$stmt->bind_param("s", $email)) {
            $resposta['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $resposta['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result

        $num_rows = mysqli_num_rows($result);

        if ($num_rows == 0) {
            $resposta['msg'] = 'Usuário não encontrado!';
        } else {
            $row = $result->fetch_assoc();

            if (!password_verify($dados['pass'], $row['pass'])) {
                $resposta['msg'] = 'Senha não confere!';
            } else {
                $resposta['id'] = $row['id'];
            }
        }
        return $resposta;
    }

    /////////////////VERIFICA SISTEMA - ATIVO OU NÃO
    public function CheckBloqueioSistema() {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $resposta = ['erro' => '', 'msg' => '', 'sistema' => []];

        if (!($stmt = $mysqli->prepare("SELECT id, bloqueio FROM sistemas WHERE nome = ?"))) {
            $resposta['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = 'Assistência ao Cidadão';

        if (!$stmt->bind_param("s", $nome)) {
            $resposta['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $resposta['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result

        $num_rows = mysqli_num_rows($result);

        if ($num_rows == 0) {
            $resposta['msg'] = 'Usuário não encontrado!';
        } else {
            $resposta['sistema'] = $result->fetch_assoc();

            
        }
        return $resposta;

    }

    public function getUser($id)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $user = ['erro' => '', 'user' => '', 'msg' => ''];

        if (!($stmt = $mysqli->prepare("SELECT * FROM dg_users WHERE id = ?"))) {
            $user['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $user['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $user['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result

        $num_rows = mysqli_num_rows($result);

        if ($num_rows == 0) {
            $user['msg'] = 'Usuário não encontrado!';
        } else {
            $row = $result->fetch_assoc();

            $user['user'] = $row;
        }
        return $user;
    }

    public function getHash($id)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $hash = ['erro' => '', 'hash' => ''];

        if (!($stmt = $mysqli->prepare("SELECT pass FROM dg_users WHERE id = ?"))) {
            $hash['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $hash['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $hash['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $hash['hash'] = $row['pass'];

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $hash;
    }

    public function updateSenha($dados)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE dg_users SET pass=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $pass = password_hash($dados['pass'], PASSWORD_DEFAULT);
        $id = $dados['id'];


        if (!$stmt->bind_param(
            "si",
            $pass,
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
}
