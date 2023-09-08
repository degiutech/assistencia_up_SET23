<?php

class MensagemModel
{

    public function listUsers($id_outro = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'list' => ''];
        $id = $_SESSION['user']['id'];

        $list = [];

        $query = "SELECT id, nome FROM users WHERE id != ?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $id)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            //quant. de mensagens cada um
            $msgs_nao_lidas = $this->countMensagensNaoLidas($row['id'], $id);
            $row['msgs_nao_lidas'] = $msgs_nao_lidas['count'];

            $row['conversa_aberta'] = '';
            if ($id_outro == $row['id']) {
                //mensagens de conversa aberta
                $conversa_aberta_res = $this->mensagensNaoLidasConversaAberta($row['id']);
                //coloca as mensagens da conversa aberta no row
                $row['conversa_aberta'] = $conversa_aberta_res['mensagens'];
                
                //altera apenas as mensagens com id de origem
                $this->updateLidaOrigem($row['id'], $id);

            }

            $list[] = $row;
        }

        $res['list'] = $list;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function countMensagensNaoLidas($id_origem, $id_destino)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT id FROM mensagens WHERE id_origem='$id_origem' AND id_destino='$id_destino' AND lida=0";

            $result = mysqli_query($mysqli, $query);

            $rows = mysqli_num_rows($result);

            $res['count'] = $rows;
        }

        return $res;
    }

    //Trazer as mensagens que tenha meu id e o id do outro
    public function mensagensNaoLidasConversaAberta($id_origem) {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'mensagens' => ''];
        $mensagens = [];

        $id_user = $_SESSION['user']['id'];

        $query = "SELECT * FROM mensagens WHERE (id_origem=? OR id_origem=?) AND (id_destino=? OR id_destino=?) AND lida=0";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iiii", $id_user, $id_origem, $id_user, $id_origem)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $mensagens[] = $row;
        }
        $res['mensagens'] = $mensagens;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;

    }

    public function getMensagens($id_user1, $id_user2)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'mensagens' => ''];
        $mensagens = [];

        $query = "SELECT * FROM mensagens WHERE (id_origem=? OR id_origem=?) AND (id_destino=? OR id_destino=?)";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iiii", $id_user1, $id_user2, $id_user1, $id_user2)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $mensagens[] = $row;
        }
        $res['mensagens'] = $mensagens;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function insert($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['erro' => '', 'id_mensagem' => ''];

        //Se não existe...
        if (!($stmt = $mysqli->prepare("INSERT INTO mensagens (id_origem, id_destino, mensagem) 
        VALUES(?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_origem = $dados['id_origem'];
        $id_destino = $dados['id_destino'];
        $mensagem = $dados['mensagem'];

        if (!$stmt->bind_param(
            "iis",
            $id_origem,
            $id_destino,
            $mensagem
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['id_mensagem'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    //Altera apenas as que tenham id de origem
    public function updateLidaOrigem($id_origem, $id_destino)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE mensagens SET lida=? WHERE lida=0 AND id_origem=? AND id_destino=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $lida = 1;

        if (!$stmt->bind_param(
            "sii",
            $lida,
            $id_origem,
            $id_destino
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

    //Altera todas as mensagens para Lida
    public function updateLida($id_origem, $id_destino)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE mensagens SET lida=? WHERE lida=0 AND (id_origem=? OR id_origem=?) AND (id_destino=? OR id_destino=?)";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $lida = 1;

        if (!$stmt->bind_param(
            "siiii",
            $lida,
            $id_origem,
            $id_destino,
            $id_origem,
            $id_destino
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

    //Altera somente as mensagens anteriores para Lida
    public function updateLidaAnteriores($id_origem, $id_destino, $id_mensagem)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE mensagens SET lida=? WHERE lida=0 AND id!=? AND (id_origem=? OR id_origem=?) AND (id_destino=? OR id_destino=?)";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $lida = 1;

        if (!$stmt->bind_param(
            "iiiiii",
            $lida,
            $id_mensagem,
            $id_origem,
            $id_destino,
            $id_origem,
            $id_destino
            
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
