<?php

class AssistenciaModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function create($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['id_assistencia' => '', 'erro' => '', 'existe' => ''];

        $id_cidadao = $dados['id_cidadao'];
        $descricao = $dados['descricao'];
        $descricao_complemento = $dados['descricao_complemento'];
        $date_at = $dados['date_at'];
        $hora = $dados['hora'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $nome_coordenadoria = $dados['nome_coordenadoria_selecionada'];
        $sus = $dados['sus'];
        $desc_juridica = $dados['desc_juridica'];
        $num_proc_juridica = $dados['num_proc_juridica'];
        $status_assist = $dados['status_assist'];
        $status_complemento = $dados['status_complemento'];
        $id_created_by = $dados['id_created_by'];
        $name_created_by = $dados['name_created_by'];

        // Verifica se já existe
        $query = "SELECT id FROM assistencias WHERE id_cidadao='$id_cidadao' AND descricao='$descricao' AND status_assist='$status_assist'";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_execute($stmt);

        /* store the result in an internal buffer */
        mysqli_stmt_store_result($stmt);

        $num_rows = mysqli_stmt_num_rows($stmt);
        if ($num_rows != 0) {
            $result['existe'] = 'existe';
            return $result;
        } else {
            $result['existe'] = $num_rows;
        }

        //Se não existe...
        if (!($stmt = $mysqli->prepare("INSERT INTO assistencias (id_cidadao, descricao, descricao_complemento, date_at, hora, 
        id_coordenadoria, nome_coordenadoria, sus, desc_juridica, num_proc_juridica, status_assist, 
        status_complemento, id_created_by, name_created_by) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param(
            "issssissssssis",
            $id_cidadao,
            $descricao,
            $descricao_complemento,
            $date_at,
            $hora,
            $id_coordenadoria,
            $nome_coordenadoria,
            $sus,
            $desc_juridica,
            $num_proc_juridica,
            $status_assist,
            $status_complemento,
            $id_created_by,
            $name_created_by
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['id_assistencia'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function createUp($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['erro' => '', 'id_assist_update' => ''];

        //Se não existe...
        if (!($stmt = $mysqli->prepare("INSERT INTO assistencias_update (id_assistencia, id_coordenadoria, nome_coordenadoria, id_updated_by, name_updated_by, 
        status_updated, status_compl_updated) 
        VALUES(?, ?, ?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_assistencia = $dados['id_assistencia'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $nome_coordenadoria = $dados['nome_coordenadoria'];
        $id_updated_by = $dados['id_updated_by'];
        $name_updated_by = $dados['name_updated_by'];
        $status_updated = $dados['status_updated'];
        $status_compl_updated = $dados['status_compl_updated'];

        if (!$stmt->bind_param(
            "iisisss",
            $id_assistencia,
            $id_coordenadoria,
            $nome_coordenadoria,
            $id_updated_by,
            $name_updated_by,
            $status_updated,
            $status_compl_updated
        )) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['id_assist_update'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function removeAssistencia($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = '';

        $query = "DELETE * FROM assistencias WHERE id='$id'";

        if (!mysqli_query($mysqli, $query)) {
            $res = 'ERRO: ' . mysqli_error($mysqli);
        }

        $db->connClose();

        return $res;
    }

    public function allAssistencias()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];
        $assist = [];

        $query = "SELECT * FROM assistencias ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row;
            }
        }

        $res['assistencias'] = $assist;

        $db->connClose();

        return $res;
    }

    //ASSISTÊNCIAS - GERAL

    /**
     * Assistências recentes - todas
     */
    public function allAssistenciasRecentes()
    {

        $msg_assistencias = '';
        $num_registros = 0;
        $assistencias = [];

        // Consulta
        $assistencias_res = $this->allAssistencias();
        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != '' && $assistencias_res['assistencias'] != null) {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res['assistencias'] as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res['assistencias']);
            $count_ups_total = $count_ups_total;
            $count_ups       = $count_ups;
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina'    => 'Página Representante',
            'titulo_consulta'  => 'RECENTES',
            'titulo_botao'     => 'recentes',
            'nome_operador'    => $_SESSION['user']['nome'],
            'assistencias'     => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    /**
     * Updates recentes - todas
     */
    public function allUpdates()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];
        $assist = [];

        $query = "SELECT * FROM assistencias_update ORDER BY updated_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row;
            }
        }

        $res['assistencias'] = $assist;

        $db->connClose();

        return $res;
    }

    /**
     * Updates recentes - todas
     */
    public function allUpdatesRecentes()
    {

        $msg_assistencias = '';
        $num_registros = 0;
        $assistencias = [];

        // Consulta
        $assistencias_res = $this->allUpdates();
        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != '' && $assistencias_res['assistencias'] != null) {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res['assistencias'] as $ass) {

                //Pegar o primeiro registro
                $primeiro_registro_res =  $this->getAssistenciaById($ass['id_assistencia']);
                if ($primeiro_registro_res['erro'] != '') {
                    $primeiro_registro = 'ERRO';
                }

                $primeiro_registro = $primeiro_registro_res['assistencia'];

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($primeiro_registro['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id_assistencia']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                //Date_at
                $dat = $ass['updated_at'];
                $date_at = $dat;
                $ass['date_at'] = $date_at;

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res['assistencias']);
            $count_ups_total = $count_ups_total;
            $count_ups       = $count_ups;
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina'    => 'Página Representante',
            'titulo_consulta'  => 'RECENTES',
            'titulo_botao'     => 'recentes',
            'nome_operador'    => $_SESSION['user']['nome'],
            'assistencias'     => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }


    /**
     * Seleciona os id dos assistidos na tabela assietencias
     */
    public function allIdsAssistidos()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'ids' => ''];
        $assist = [];

        $query = "SELECT id_cidadao FROM assistencias ";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assist[] = $row['id_cidadao'];
            }
        }

        $res['ids'] = $assist;

        $db->connClose();

        return $res;
    }

    /**
     * Seleciona os id dos assistidos na tabela assietencias de uma Coordenadoria
     */
    public function allIdsAssistidosByCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'cidadao' => ''];
        $cidadao = [];

        $query = "SELECT id_cidadao FROM assistencias WHERE id_coordenadoria=?";

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
            $cidadao[] = $row;
        }

        $res['cidadao'] = $cidadao;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    // Assistências não finalizadas - Geral
    public function assNaoFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE status_assist!='Finalizada' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'NÃO FINALIZADAS',
            'titulo_botao' => 'não finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    // Assistências não finalizadas - Geral
    public function assFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE status_assist='Finalizada' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'FINALIZADAS',
            'titulo_botao' => 'finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    //Assistências por data - Geral
    public function assPorData($por_data)
    {
        if (!$por_data) {
            $pd = new DateTime();
            $por_data = $pd->format('Y-m-d');
        }

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime($por_data);
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE date_at='$por_data' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'REGISTRADAS EM ' . $data,
            'titulo_botao' => 'por data',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    // Assistências por mês e ano - Geral
    public function assMesAno($mes, $ano)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE MONTH(date_at)='$mes' AND YEAR(date_at) ='$ano' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'REGISTRADAS NO MÊS ' . $mes . '/' . $ano,
            'titulo_botao' => 'por mes',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    // Assistências por período - Geral
    public function assPeriodo($dt_inicial, $dt_final)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE date(date_at) >= '$dt_inicial' AND date(date_at) <= '$dt_final' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        // Datas inicial e final
        if (!$dt_inicial || !$dt_final) {
            $dt_inicial = '';
            $dt_final = '';
        } else {
            $dt_i = new DateTime(($dt_inicial));
            $dt_inicial = $dt_i->format('d/m/Y');
            $dt_f = new DateTime(($dt_final));
            $dt_final = $dt_f->format('d/m/Y');
        }

        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {

            $count_ups = 0;
            $count_ups_total = 0;

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                    $count_ups += count($up['assist_up']) - 1;
                    $count_ups_total += count($up['assist_up']);
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'PERÍODO DE ' . $dt_inicial . ' A ' . $dt_final,
            'titulo_botao' => 'por período',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
            'num_ups_total'    => $count_ups_total,
            'num_ups'          => $count_ups
        ];

        return $dados;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXX FIM DE FILTROS DE ASSISTÊNCIAS GERAL XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    //ASSISTÊNCIAS - POR COORDENADORIA

    /**
     * Assistências recentes - ByCoordenadoria
     */
    public function allAssistenciasRecentesByCoordenadoria($id_coordenadoria)
    {

        $msg_assistencias = '';
        $num_registros = 0;
        $assistencias = [];

        // Consulta
        $assistencias_res = $this->AssistenciasRecentesByCoordenadoria($id_coordenadoria);
        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != '' && $assistencias_res['assistencias'] != null) {

            foreach ($assistencias_res['assistencias'] as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res['assistencias']);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'RECENTES',
            'titulo_botao' => 'recentes',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências não finalizadas - ByCoordenadoria
    public function assNaoFinalizadasByCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND status_assist!='Finalizada' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'NÃO FINALIZADAS',
            'titulo_botao' => 'não finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências não finalizadas - ByCoordenadoria
    public function assFinalizadasByCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND status_assist='Finalizada' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'FINALIZADAS',
            'titulo_botao' => 'finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    //Assistências por data - ByCoordenadoria
    public function assPorDataByCoordenadoria($id_coordenadoria, $por_data)
    {
        if (!$por_data) {
            $pd = new DateTime();
            $por_data = $pd->format('Y-m-d');
        }

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime($por_data);
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND date_at='$por_data' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'REGISTRADAS EM ' . $data,
            'titulo_botao' => 'por data',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
        ];

        return $dados;
    }

    // Assistências por mês e ano - ByCoordenadoria
    public function assMesAnoByCoordenadoria($id_coordenadoria, $mes, $ano)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND MONTH(date_at)='$mes' AND YEAR(date_at) ='$ano' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'REGISTRADAS NO MÊS ' . $mes . '/' . $ano,
            'titulo_botao' => 'por data',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências por período - ByCoordenadoria
    public function assPeriodoByCoordenadoria($id_coordenadoria, $dt_inicial, $dt_final)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND date(date_at) >= '$dt_inicial' AND date(date_at) <= '$dt_final' ORDER BY created_at DESC LIMIT 200";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        // Datas inicial e final
        if (!$dt_inicial || !$dt_final) {
            $dt_inicial = '';
            $dt_final = '';
        } else {
            $dt_i = new DateTime(($dt_inicial));
            $dt_inicial = $dt_i->format('d/m/Y');
            $dt_f = new DateTime(($dt_final));
            $dt_final = $dt_f->format('d/m/Y');
        }

        $titulo_minhas = '';


        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
            $titulo_minhas = 'PERÍODO DE ' . $dt_inicial . ' A ' . $dt_final;
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_consulta' => 'PERÍODO DE ' . $dt_inicial . ' A ' . $dt_final,
            'titulo_botao' => 'por período',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros,
        ];

        return $dados;
    }

    //NOVOS FILTROS
    public function filtrosAssistenciasByCoordenadoria($dados)
    {

        $id_coordenadoria = $dados['id_coordenadoria'];
        $data = $dados['data'];
        $mes = $dados['mes'];
        $ano = $dados['ano'];
        $dt_inicial = $dados['dt_inicial'];
        $dt_final = $dados['dt_final'];

        $res = ['erro' => '', 'assistencias' => '', 'nao_finalizadas' => '', 'finalizadas' => ''];

        $db = new Database();
        $mysqli = $db->getConection();

        if ($dados['input_datas'] == 'data') {
            $assistencias = $this->assPorDataByCoordenadoria($dados['id_coordenadoria'], $dados['data']);
        }
        if ($dados['input_datas'] == 'mes_ano') {
            return $this->assMesAnoByCoordenadoria($dados['id_coordenadoria'], $dados['mes'], $dados['ano']);
        }
        if ($dados['input_datas'] == 'periodo') {
            $query = "SELECT * FROM assistencias WHERE id_coordenadoria='$id_coordenadoria' AND date(date_at) >= '$dt_inicial' AND date(date_at) <= '$dt_final' ORDER BY created_at DESC LIMIT 200";
        }

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            
            $count_nao_finalizadas = 0;
            $count_finalizadas = 0;


            while ($row = $result->fetch_assoc()) {

                
                 //Não finalizadas
                 if ($row['status_assist'] == 'Iniciada') {
                    $count_nao_finalizadas += 1;
                }
                //Finalizadas
                if ($row['status_assist'] == 'Finalizada') {
                    $count_finalizadas += 1;
                }

                $assistencias_res[] = $row;


            }

        }

        $db->connClose();

        
        if ($assistencias_res != null && $assistencias_res != '') {

            for ($i = 0; $i < count($assistencias_res); $i++) {

                //Pegar Cidadão
                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($assistencias_res[$i]['id_cidadao']);
                $assistencias_res[$i]['nome_cidadao'] = $cidadao['cidadao']['nome'];

                //Pegar updates
                $up = $this->getAssistenciasUpdate($assistencias_res[$i]['id']);
                $assistencias_res[$i]['updates'] = $up['assist_up'];
                $assistencias_res[$i]['status_atual'] = $up['assist_up'][0]['status_updated'];
                $dt_up = date_create($up['assist_up'][0]['updated_at']);

                if (count($up) == 1) {
                    $assistencias_res[$i]['ultima_atualizacao'] = 'Não há atualizações';
                    $assistencias_res[$i]['desc_ultima_atualizacao'] = 'Não há atualizações';
                } else {
                    $assistencias_res[$i]['ultima_atualizacao'] = date_format($dt_up, 'd/m/Y');
                    $assistencias_res[$i]['desc_ultima_atualizacao'] = $up['assist_up'][0]['status_compl_updated'];
                }
            }

            
        } else {
            $assistencias_res = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $res['assistencias'] = $assistencias_res;
        $res['nao_finalizadas'] = $count_nao_finalizadas;
        $res['finalizadas'] = $count_finalizadas;


        return $res;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXX FIM DE FILTROS DE ASSISTÊNCIAS POR COORDENADORIA XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


    /**
     * Pega a Assistência pelo id
     */
    public function getAssistenciaById($id_assistencia)
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

    /**
     * Pega todas as updates de Assistencia alerada pelo id da Assistência na tabela assistencias_update
     */

    //SERIA GET ASSISTENCIA E NÃO ASSISTENCIAS
    public function getAssistenciasUpdate($id_assistencia)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assist_up' => ''];

        $query = "SELECT * FROM assistencias_update WHERE id_assistencia=? ORDER BY updated_at DESC";

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

        $updates = [];


        while ($row = $result->fetch_assoc()) {
            $updates[] = $row;
        }

        $res['assist_up'] = $updates;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Add dados jurídicos
    public function addDadosJuridicos($id_assistencia, $desc_juridica, $num_proc_juridica)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = '';

        if (!($stmt = $mysqli->prepare("UPDATE assistencias SET desc_juridica = ?, num_proc_juridica = ? WHERE id = ?"))) {
            $result = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ssi", $desc_juridica, $num_proc_juridica, $id_assistencia)) {
            $result =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result = true;
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    public function updateStatus($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['erro' => '', 'id_updated_status' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO assistencias_update (id_assistencia, id_coordenadoria, nome_coordenadoria, id_updated_by, name_updated_by, status_updated, status_compl_updated) 
        VALUES(?, ?, ?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id_assistencia = $dados['id_assistencia'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $nome_coordenadoria = $dados['nome_coordenadoria'];
        $id_updated_by = $dados['id_updated_by'];
        $name_updated_by = $dados['name_updated_by'];
        $status_updated = $dados['status_updated'];
        $status_compl_updated = $dados['status_compl_updated'];

        if (!$stmt->bind_param("iisisss", $id_assistencia, $id_coordenadoria, $nome_coordenadoria, $id_updated_by, $name_updated_by, $status_updated, $status_compl_updated)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result['id_updated_status'] = mysqli_insert_id($mysqli);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    /**
     * Verifica se existe assistencia_update
     */
    public function updateStatusExiste($status_updated, $status_compl_updated)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'num_rows' => ''];

        $query = "SELECT status_updated, status_compl_updated FROM assistencias_update WHERE status_updated=? AND status_compl_updated=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ss", $status_updated, $status_compl_updated)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $stmt->store_result();

        $num_rows = $stmt->num_rows;

        $res['num_rows'] = $num_rows;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Insere SUS na Assistencia
    public function insertSus($id_assistencia, $sus)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        // $result = ['id' => '', 'erro' => ''];

        $result = ['erro' => '', 'affected_rows' => ''];

        $query = "UPDATE assistencias SET sus=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param(
            "si",
            $sus,
            $id_assistencia
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

    //Assistências do Cidadão
    public function allAssistenciasByCidadao($id_cidadao)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];

        $query = "SELECT * FROM assistencias WHERE id_cidadao=? ORDER BY created_at DESC";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_cidadao)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $assistencias = [];


        while ($row = $result->fetch_assoc()) {
            $assistencias[] = $row;
        }

        $res['assistencias'] = $assistencias;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Assistências de Cidadão por Coordenadoria
     */
    public function allAssistenciasByCidadaoCoordenadoria($id_cidadao, $id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];

        $query = "SELECT * FROM assistencias WHERE id_cidadao=? AND id_coordenadoria=? ORDER BY created_at DESC";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ii", $id_cidadao, $id_coordenadoria)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $assistencias = [];


        while ($row = $result->fetch_assoc()) {
            $assistencias[] = $row;
        }

        $res['assistencias'] = $assistencias;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Assistências de Cidadão por Representante
     */
    public function allAssistenciasByCidadaoRepresentante($id_cidadao, $id_representante)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];

        $query = "SELECT * FROM assistencias WHERE id_cidadao=? AND id_created_by=? ORDER BY created_at DESC";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("ii", $id_cidadao, $id_representante)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $assistencias = [];


        while ($row = $result->fetch_assoc()) {
            $assistencias[] = $row;
        }

        $res['assistencias'] = $assistencias;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Assistências de Cidadão por oPERADOR
     */
    public function allAssistenciasByOperador($id_operador)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];

        $query = "SELECT * FROM assistencias WHERE id_created_by=? ORDER BY created_at DESC";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_operador)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $assistencias = [];


        while ($row = $result->fetch_assoc()) {
            $assistencias[] = $row;
        }

        $res['assistencias'] = $assistencias;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    //Updates by operador
    public function updatesByOperador($id_operador, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'nao_finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_operador)) {
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

    /**
     * Updates por operador por data
     */
    public function updatesByOperadorByData($id_operador, $dia, $mes, $ano, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'não finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND DAY(updated_at)=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("isss", $id_operador, $dia, $mes, $ano)) {
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

    public function updatesByOperadorMesAno($id_operador, $mes, $ano, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];

        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'não finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND MONTH(updated_at)=? AND YEAR(updated_at)=? AND status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iss", $id_operador, $mes, $ano)) {
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

    public function updatesByOperadorPeriodo($id_operador, $dt_inicial, $dt_final, $status = null)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'updates' => ''];


        if (!$status) {
            $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC LIMIT 200";
        } else {
            if ($status == 'todas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND date(updated_at) >= ? AND date(updated_at) <= ? ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'não finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated != 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
            if ($status == 'finalizadas') {
                $query = "SELECT * FROM assistencias_update WHERE id_updated_by=? AND date(updated_at) >= ? AND date(updated_at) <= ? AND status_updated = 'Finalizada' ORDER BY updated_at DESC LIMIT 200";
            }
        }

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("iss", $id_operador, $dt_inicial, $dt_final)) {
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

    public function listAssistenciasComUpdatesByOperador($id_operador)
    {

        // $res = ['erro' => '', 'assistencias' => ''];

        // $updates_res = $this->updatesByOperador($id_operador);
        // if ($updates_res['erro'] != '') {
        //     return $res['erro'] = 'ERRO ao buscar atualizações de Assistências!';
        // }
        // $updates = $updates_res['updates'];

        // //Forma o array de ids de Assistências
        // $array_id_ass = [];
        // foreach ($updates as $id_ass) {
        //     if (!in_array($id_ass['id_assistencia'], $updates)) {
        //         $array_id_ass[] = $id_ass['id_assistencia'];
        //     }
        // }

        // //Forma a saída
        // $assistencias = [];
        // foreach ($array_id_ass as $ids) {
        //     foreach ($updates as $up) {
        //         if ($up['id_assistencia'] == $ids) {
        //             $assistencias[$ids][] = $up['id'];
        //         }
        //     }
        // }

        // return $assistencias;
    }

    /**
     * Data recente de update_assistencias
     */
    public function dataRecenteUpdate($id_assistencia)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $query = "SELECT MAX(updated_at) AS data_recente FROM assistencias_update WHERE id_assistencia = '$id_assistencia'";

        $res = $mysqli->query($query);
        $result = mysqli_fetch_object($res);

        return $result->data_recente;
    }

    public function updateAssistByData($updated_at, $id_assistencia)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencia_update' => ''];

        $query = "SELECT * FROM assistencias_update WHERE updated_at=? AND id_assistencia=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("si", $updated_at, $id_assistencia)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $assistencias = [];

        $res['assistencia_update'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function finalizarAssistencia($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'affected_rows' => ''];

        $query = "UPDATE assistencias SET status_assist=?, status_complemento=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $status_assist = $dados['status_assist'];
        $status_complemento = $dados['status_complemento'];
        $id_assistencia = $dados['id_assistencia'];

        if (!$stmt->bind_param("ssi", $status_assist, $status_complemento, $id_assistencia)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $res['affected_rows'] = mysqli_stmt_affected_rows($stmt);
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Assistências por Coordenadoria
     */
    public function AssistenciasRecentesByCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'assistencias' => ''];

        $query = "SELECT * FROM assistencias WHERE id_coordenadoria=? ORDER BY created_at DESC LIMIT 200";

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

        $assistencias = [];


        while ($row = $result->fetch_assoc()) {
            $assistencias[] = $row;
        }

        $res['assistencias'] = $assistencias;

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    // CONTADORES

    //Admin

    /**
     * Conta todas as Assistências
     */
    public function countAssistenciasTodas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências não finalizadas
     */
    public function countAssistenciasNaoFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist != 'Finalizada'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências finalizadas
     */
    public function countAssistenciasFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist = 'Finalizada'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências registradas no mês atual
     */
    public function countAssistenciasRegistradasMesAtual($mes)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE MONTH(date_at) = '$mes'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as atualizações de Assistências no mês atual
     */
    public function countAssistenciasUpdatesMesAtual($mes)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE MONTH(updated_at) = '$mes'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //XXXXXXXXXXXXXXXXXXXXXX

    //CONTA OS UPDATES
    /**
     * Conta todas as Updates
     */
    public function countUpdatesTodas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Updates não finalizadas
     */
    public function countUpdatesNaoFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE status_updated != 'Finalizada'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Updates finalizadas
     */
    public function countUpdatesFinalizadas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE status_updated = 'Finalizada'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Updates registradas no mês atual
     */
    public function countUpdatesRegistradasMesAtual($mes)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE MONTH(updated_at) = '$mes'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //XXXXXXXXXXXXXXXXXXXXXX FIM COUNT UPDATES

    //Coordenador
    /**
     * Conta todas as Assistências de uma Coordenadoria (por nome da Coordenadoria)
     */
    public function countAssistenciasCoordenadoria($coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE nome_coordenadoria='$coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta todas as Assistências de uma Coordenadoria (por id_coordenadoria)
     */
    public function countAssistenciasByIdCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE id_coordenadoria='$id_coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências não finalizadas de uma Coordenadoria pelo nome da Coordenadoria
     */
    public function countAssistenciasNaoFinalizadasCoordenadoria($coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist != 'Finalizada' AND nome_coordenadoria = '$coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências não finalizadas de uma Coordenadoria pelo id da Coordenadoria
     */
    public function countAssistenciasNaoFinalizadasByIdCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist != 'Finalizada' AND id_coordenadoria = '$id_coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências finalizadas de uma Coordenadoria
     */
    public function countAssistenciasFinalizadasCoordenadoria($coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist = 'Finalizada' AND nome_coordenadoria='$coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências finalizadas de uma Coordenadoria pelo id da Coordenadoria
     */
    public function countAssistenciasFinalizadasByIdCoordenadoria($id_coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE status_assist = 'Finalizada' AND id_coordenadoria='$id_coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as Assistências registradas no mês atual de uma Coordenadoria
     */
    public function countAssistenciasRegistradasMesAtualCoordenadoria($mes, $coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias WHERE MONTH(date_at) = '$mes' AND nome_coordenadoria='$coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Conta as atualizações de Assistências no mês atual de uma Coordenadoria
     */
    public function countAssistenciasUpdatesMesAtualCoordenadoria($mes, $coordenadoria)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM assistencias_update WHERE MONTH(updated_at) = '$mes' AND nome_coordenadoria='$coordenadoria'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //GERENCIAMENTO DE ASSISTÊNCIAS POR COORDENADORIA

    //ASSISTÊNCIAS POR OPERADOR
    //Recentes
    public function assByOperadorRecentes($id_operador)
    {

        $msg_assistencias = '';
        $num_registros = 0;
        $assistencias = [];
        $assistencias_res = $this->allAssistenciasByOperador($id_operador);
        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != '' && $assistencias_res['assistencias'] != null) {

            foreach ($assistencias_res['assistencias'] as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res['assistencias']);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => 'Assistências recentes registradas por',
            'titulo_botao' => 'recentes',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências não finalizadas por Operador
    public function assByOperadorNaoFinalizadas($id_operador)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND status_assist!='Finalizada' ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => 'Assistências não finalizadas registradas por',
            'titulo_botao' => 'não finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências não finalizadas por Operador
    public function assByOperadorFinalizadas($id_operador)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND status_assist='Finalizada' ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => 'Assistências finalizadas registradas por',
            'titulo_botao' => 'finalizadas',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    //Assistências por data por Operador
    public function assByOperadorPorData($id_operador, $por_data)
    {
        if (!$por_data) {
            $pd = new DateTime();
            $por_data = $pd->format('Y-m-d');
        }

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime($por_data);
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND date_at='$por_data' ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null) {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => 'Assistências registradas em ' . $data . ' por',
            'titulo_botao' => 'por data',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Assistências por mês e ano por Operador
    public function assByOperadorMesAno($id_operador, $mes, $ano)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND MONTH(date_at)='$mes' AND YEAR(date_at) ='$ano' ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {


            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => 'Assistências registradas no mês ' . $mes . '/' . $ano . ' por',
            'titulo_botao' => 'por mes',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }

    // Minhas Assistências por período
    public function assByOperadorPeriodo($id_operador, $dt_inicial, $dt_final)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $erro = '';
        $assistencias_res = [];

        $dt = new DateTime();
        $data = $dt->format('d/m/Y');

        $msg_assistencias = '';
        $num_registros = 0;

        // $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND mes='$mes' AND ano ='$ano' ORDER BY created_at DESC";
        $query = "SELECT * FROM assistencias WHERE id_created_by='$id_operador' AND date(date_at) >= '$dt_inicial' AND date(date_at) <= '$dt_final' ORDER BY created_at DESC";

        if (!$result = mysqli_query($mysqli, $query)) {
            $erro = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {
                $assistencias_res[] = $row;
            }
        }

        $db->connClose();

        // Datas inicial e final
        if (!$dt_inicial || !$dt_final) {
            $dt_inicial = '';
            $dt_final = '';
        } else {
            $dt_i = new DateTime(($dt_inicial));
            $dt_inicial = $dt_i->format('d/m/Y');
            $dt_f = new DateTime(($dt_final));
            $dt_final = $dt_f->format('d/m/Y');
        }

        $titulo_minhas = '';


        if ($erro == '' && $assistencias_res != null && $assistencias_res != '') {

            foreach ($assistencias_res as $ass) {

                $cd = new CidadaoModel;
                $cidadao = $cd->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }

            $num_registros = count($assistencias_res);
            $titulo_minhas = 'Assistências registradas no período de ' . $dt_inicial . ' a ' . $dt_final . ' por';
        } else {
            $assistencias = '';
            $msg_assistencias = 'NÃO HÁ REGISTROS!';
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'titulo_minhas' => $titulo_minhas,
            'titulo_botao' => 'por período',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'data'          => $data,
            'msg_assistencias' => $msg_assistencias,
            'num_registros'    => $num_registros
        ];

        return $dados;
    }
}
