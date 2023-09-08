<?php

class FaqModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function create($pergunta)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $id_user = $_SESSION['user']['id'];
        $nome_user = $_SESSION['user']['nome'];
        $acesso = $_SESSION['user']['acesso'];

        $result = ['id' => '', 'erro' => ''];

        $existe = $mysqli->query("SELECT pergunta, id_user FROM faqs WHERE pergunta = '$pergunta'");
        if ($existe->num_rows > 0) {
            $result['erro'] = 'Este FAQ já existe!';
            return $result;
        }


        $query = "INSERT INTO faqs (pergunta, id_user, nome_user, acesso) VALUES(?, ?, ?, ?)";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("siss", $pergunta, $id_user, $nome_user, $acesso)) {
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

    public function perguntas($acesso) {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'perguntas' => ''];
        $perguntas = [];

        $query = "SELECT * FROM faqs WHERE acesso='$acesso' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $perguntas[] = $row;
            }
        }

        $res['perguntas'] = $perguntas;

        $db->connClose();

        return $res;

    }

    public function naoRespondidasGeral() {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'perguntas' => ''];
        $perguntas = [];

        $query = "SELECT * FROM faqs WHERE resposta IS NULL ORDER BY created_at ASC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $perguntas[] = $row;
            }
        }

        $res['perguntas'] = $perguntas;

        $db->connClose();

        return $res;

    }

    public function responder($dados) {

        $res = ['erro' => '', 'affected_rows' => ''];

        $db = new Database();
        $mysqli = $db->getConection();

        $query = "UPDATE faqs SET resposta=?, id_updated_by=?, name_updated_by=?, updated_at=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $resposta = $dados['resposta'];
        $id_updated_by = $_SESSION['user']['id'];
        $name_updated_by = $_SESSION['user']['nome'];
        $updated_at = date('Y-m-d');
        $id = $dados['id'];

        if (!$stmt->bind_param(
            "sissi",
            $resposta,
            $id_updated_by,
            $name_updated_by,
            $updated_at,
            $id
        )) {
            $res['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $res['affected_rows'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }
}
