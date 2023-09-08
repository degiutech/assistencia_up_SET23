<?php

class AssistenciaUpModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }



    //All Updates recentes
    public function allUpdatesRecentes()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];
        $assist = [];

        $query = "SELECT * FROM assistencias_update ORDER BY updated_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row;
            }
        }

        $res['updates'] = $assist;

        $db->connClose();

        return $res;
    }

    public function allUpdatesNaoFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];
        $assist = [];

        $query = "SELECT * FROM assistencias_update WHERE status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row;
            }
        }

        $res['updates'] = $assist;

        $db->connClose();

        return $res;
    }

    public function allUpdatesFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];
        $assist = [];

        $query = "SELECT * FROM assistencias_update WHERE status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row;
            }
        }

        $res['updates'] = $assist;

        $db->connClose();

        return $res;
    }

    public function allUpdatesByData($dia, $mes, $ano, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if ($status) {

            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC";
            }
        } else {
            $query = "SELECT * FROM assistencias_update WHERE DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("sss", $dia, $mes, $ano)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function allUpdatesByMesAno($mes, $ano, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if ($status) {

            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC";
            }
        } else {
            $query = "SELECT * FROM assistencias_update WHERE MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $mes, $ano)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function allUpdatesByPeriodo($dt_inicial, $dt_final, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if ($status) {

            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC";
            }
        } else {
            $query = "SELECT * FROM assistencias_update WHERE date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC LIMIT 200";
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $dt_inicial, $dt_final)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //POR COORDENADORIA - recentes, não finalizadas ou finalizadas
    public function allUpdatesRecentesByIdCoordenadoria($id_coordenadoria, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_coordenadoria)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Updates por data e por Coordenadoria
    public function allUpdatesByDataByCoordenadoria($id_coordenadoria, $dia, $mes, $ano, $status)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC";
            }
            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("isss", $id_coordenadoria, $dia, $mes, $ano)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Updates por mes e ano e por Coordenadoria
    public function allUpdatesByMesAnoByCoordenadoria($id_coordenadoria, $mes, $ano, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC";
            }
            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iss", $id_coordenadoria, $mes, $ano)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Updates por período e por Coordenadoria
    public function allUpdatesByPeriodoByCoordenadoria($id_coordenadoria, $dt_inicial, $dt_final, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC";
            }
            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated != 'Finalizada' ORDER BY updated_at DESC";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_coordenadoria=? AND date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated = 'Finalizada' ORDER BY updated_at DESC";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iss", $id_coordenadoria, $dt_inicial, $dt_final)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['updates'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function getAssistencia($id_assistencia)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencia' => ''];

        $query = "SELECT * FROM assistencias WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_assistencia)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();
        $res['assistencia'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //CONTADORES DE UPDATES

    //Contador de update por coordenadoria
    public function countUpByCoordenadoria($id_coordenadoria, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_coordenadoria='$id_coordenadoria'";
            if ($status) {

                if ($status == 'nao_finalizadas') {
                    $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_coordenadoria='$id_coordenadoria' AND status_updated != 'Finalizada'";
                }
                if ($status == 'finalizadas') {
                    $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_coordenadoria='$id_coordenadoria' AND status_updated = 'Finalizada'";
                }
                if ($status == 'geral') {
                    $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_coordenadoria='$id_coordenadoria'";
                }
            }

            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Updates geral, não finalizadas ou finalizadas
     */
    public function countUpdates($status)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        if (!$mysqli) {
            $res = "Connection failed: " . mysqli_connect_error();
        } else {
            //geral
            if ($status == 'geral') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update";
            }
            //não finalizadas
            if ($status == 'não finalizadas') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE status_updated != 'Finalizada'";
            }
            //finalizadas
            if ($status == 'finalizadas') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE status_updated = 'Finalizada'";
            }


            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //CONTADORES DE UPDATES POR OPERADOR
    public function countUpdatesByOperador($id_operador, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => 0];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            //geral
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_updated_by = '$id_operador'";
            if ($status == 'geral') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_updated_by = '$id_operador'";
            }
            //não finalizadas
            if ($status == 'nao_finalizadas') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_updated_by = '$id_operador' AND status_updated != 'Finalizada'";
            }
            //finalizadas
            if ($status == 'finalizadas') {
                $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE id_updated_by = '$id_operador' AND status_updated = 'Finalizada'";
            }


            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }
}
