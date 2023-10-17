<?php

class DgAssistenciaModel
{

    public function __construct()
    {
    }

    public function createOperador($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO users (nome, email, acesso, bloqueio, senha) 
        VALUES(?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $email = $dados['email'];
        $acesso = $dados['acesso'];
        $bloqueio = $dados['bloqueio'];
        $pass = password_hash($dados['pass'], PASSWORD_DEFAULT);


        if (!$stmt->bind_param(
            "sssss",
            $nome,
            $email,
            $acesso,
            $bloqueio,
            $pass
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['id'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function updateOperador($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $nome = $dados['nome'];
        $email = $dados['email'];
        $id_updated_by = $_SESSION['user']['id'];
        $nome_updated_by = 'Degiutech';
        $updated_at = date('Y-m-d H:i:s');
        $id = $dados['id'];

        //Campo senha vazio
        if ($dados['pass'] == '') {

            $query = "UPDATE users SET nome=?, email=?, id_updated_by=?, nome_updated_by=?, updated_at=? WHERE id=?";

            if (!($stmt = $mysqli->prepare($query))) {
                $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }

            if (!$stmt->bind_param(
                "ssissi",
                $nome,
                $email,
                $id_updated_by,
                $nome_updated_by,
                $updated_at,
                $id
            )) {
                $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            //Com senha
        } else {

            $query = "UPDATE users SET nome=?, email=?, senha=?, id_updated_by=?, nome_updated_by=?, updated_at=? WHERE id=?";

            if (!($stmt = $mysqli->prepare($query))) {
                $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }

            $senha = password_hash($dados['pass'], PASSWORD_DEFAULT);

            if (!$stmt->bind_param(
                "sssissi",
                $nome,
                $email,
                $senha,
                $id_updated_by,
                $nome_updated_by,
                $updated_at,
                $id
            )) {
                $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
        }
        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function getSistema($id)
    {

        $db = new DatabaseDg();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'sistema' => ''];

        if (!($stmt = $mysqli->prepare("SELECT * FROM sistemas WHERE id = ?"))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$result = $stmt->get_result()) {
            $res['erro'] = "Result failed: " . $stmt->errno . ") " . $stmt->error;
        } else {
            $res['sistema'] = $result->fetch_assoc();
        }

        return $res;
    }
}
