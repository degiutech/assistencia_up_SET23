<?php

class CidadaoModel
{

    private $sessao_acesso;

    public function __construct()
    {
        $this->sessao_acesso = Sessao::sessaoUser();
    }

    public function verificaCPF($cpf)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'id' => ''];

        $query = "SELECT id FROM cidadaos WHERE cpf=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $cpf)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $id = '';

        if (!$stmt->bind_result($id)) {
            $res['erro'] = "Bind result failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->fetch()) {
            $res['erro'] = "Fetch failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $res['id'] = $id;
        }

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    public function create($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO cidadaos (nome, sexo, email, data_nascimento, celular, bloqueio, cpf, rg, orgao_expedidor,
        titulo_eleitor, zona_eleitoral, secao_eleitoral, sus, cep, logradouro, numero, complemento, bairro, cidade,
        uf, ibge, facebook, twitter, instagram, telegram, skype, tiktok) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $sexo = $dados['sexo'];
        $email = $dados['email'];
        $data_nascimento = $dados['data_nasc'];
        $celular = $dados['celular'];
        $bloqueio = 'Desbloqueado';
        $cpf = $dados['cpf'];
        $rg = $dados['rg'];
        $orgao_expedidor = $dados['orgao_expedidor'];
        $titulo_eleitor = $dados['titulo'];
        $zona_eleitoral = $dados['zona'];
        $secao_eleitoral = $dados['secao'];
        $sus = $dados['sus'];

        $cep = $dados['cep'];
        $logradouro = $dados['logradouro'];
        $numero = $dados['numero'];
        $complemento = $dados['complemento'];
        $bairro = $dados['bairro'];
        $cidade = $dados['cidade'];
        $uf = $dados['uf'];
        $ibge = $dados['ibge'];
        $facebook = $dados['facebook'];
        $twitter = $dados['twitter'];
        $instagram = $dados['instagram'];
        $telegram = $dados['telegram'];
        $skype = $dados['skype'];
        $tiktok = $dados['tiktok'];


        if (!$stmt->bind_param(
            "sssssssssssssssssssssssssss",
            $nome,
            $sexo,
            $email,
            $data_nascimento,
            $celular,
            $bloqueio,
            $cpf,
            $rg,
            $orgao_expedidor,
            $titulo_eleitor,
            $zona_eleitoral,
            $secao_eleitoral,
            $sus,
            $cep,
            $logradouro,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $uf,
            $ibge,
            $facebook,
            $twitter,
            $instagram,
            $telegram,
            $skype,
            $tiktok
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

    public function edit($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        // $result = ['id' => '', 'erro' => ''];

        $result = ['erro' => '', 'affected_rows' => ''];

        $query = "UPDATE cidadaos SET nome=?, sexo=?, email=?, celular=?, data_nascimento=?, cep=?, logradouro=?,
        numero=?, complemento=?, bairro=?, cidade=?, uf=?, ibge=?, cpf=?, rg=?, orgao_expedidor=?, titulo_eleitor=?, 
        zona_eleitoral=?, secao_eleitoral=?, sus=?, id_updated_by=?, nome_updated_by=?, updated_at=?,
        facebook=?, twitter=?, instagram=?, telegram=?, skype=?, tiktok=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $sexo = $dados['sexo'];
        $email = $dados['email'];
        $celular = $dados['celular'];
        $data_nascimento = $dados['data_nasc'];
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
        $titulo_eleitor = $dados['titulo'];
        $zona_eleitoral = $dados['zona'];
        $secao_eleitoral = $dados['secao'];
        $sus = $dados['sus'];
        $id_updated_by = $_SESSION['user']['id'];
        $nome_updated_by = $_SESSION['user']['nome'];
        $updated_at = date('Y-m-d H:i:s');
        $facebook = $dados['facebook'];
        $twitter = $dados['twitter'];
        $instagram = $dados['instagram'];
        $telegram = $dados['telegram'];
        $skype = $dados['skype'];
        $tiktok = $dados['tiktok'];
        $id = $dados['id_cidadao'];


        if (!$stmt->bind_param(
            "ssssssssssssssssssssissssssssi",
            $nome,
            $sexo,
            $email,
            $celular,
            $data_nascimento,
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
            $sus,
            $id_updated_by,
            $nome_updated_by,
            $updated_at,
            $facebook,
            $twitter,
            $instagram,
            $telegram,
            $skype,
            $tiktok,
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

    public function cidadao($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'cidadao' => ''];

        $query = "SELECT * FROM cidadaos WHERE id=?";

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
        $res['cidadao'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Pega o nome e o id do cidadão
     */
    public function getNomeIdCidadao($id_cidadao)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'cidadao' => ''];

        $query = "SELECT nome, id FROM cidadaos WHERE id=?";

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
        $res['cidadao'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Últimos 200 cadastros de Cidadão
     */
    public function cadastrosRecentes()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'cidadaos' => ''];
        $cidadaos = [];

        if ($mysqli->connect_error) {
            $res['erro'] = "Connection failed: " . $mysqli->connect_error;
        }

        if (!$result = $mysqli->query("SELECT * FROM cidadaos ORDER BY created_at DESC LIMIT 200")) {
            $res['erro'] = 'ERRO ao buscar Cidadãos!';
        } else {
            while ($row = $result->fetch_assoc()) {
                $cidadaos[] = $row;
            }
            $res['cidadaos'] = $cidadaos;
        }

        $db->connClose();

        return $res;
    }

    public function cidadaosTodos()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'cidadaos' => ''];
        $cidadaos = [];

        if ($mysqli->connect_error) {
            $res['erro'] = "Connection failed: " . $mysqli->connect_error;
        }

        if (!$result = $mysqli->query("SELECT * FROM cidadaos ORDER BY created_at DESC")) {
            $res['erro'] = 'ERRO ao buscar Cidadãos!';
        } else {
            while ($row = $result->fetch_assoc()) {
                $cidadaos[] = $row;
            }
            $res['cidadaos'] = $cidadaos;
        }

        $db->connClose();

        return $res;
    }

    /**
     * Busca por Cidadão
     */
    public function search($texto)
    {

        $res = ['num_rows' => 0, 'res' => []];

        $conn = new Database();
        $mysqli = $conn->getConection();

        $query = "SELECT id, nome FROM cidadaos WHERE UPPER(cpf) LIKE '%$texto%' 
        OR UPPER(nome) LIKE '%$texto%' 
        OR UPPER(titulo_eleitor) LIKE '%$texto%' LIMIT 15";

        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $res['res'][] = [
                    'id' => $row['id'],
                    'nome' => $row['nome']
                ];
            }
            $res['num_rows'] = count($res['res']);
        }

        mysqli_free_result($result);
        $mysqli->close();

        return json_encode($res);
    }

    //CONTADORES
    //Todos
    public function countCidadaosTodos()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM cidadaos";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    //Cadastrados no mês atual
    public function countCadastradosMes($mes)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM cidadaos WHERE MONTH(created_at) = '$mes'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }

    public function cidadaosAssistenciasAtivas()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $assistencias_res = ['erro' => '', 'ids_cidadaos' => ''];

        $ids_cidadaos = [];

        if ($mysqli->connect_error) {
            $assistencias_res['erro'] = "Connection failed: " . $mysqli->connect_error;
        }

        if (!$result = $mysqli->query("SELECT id_cidadao FROM assistencias WHERE status_assist != 'Finalizada' ORDER BY created_at DESC LIMIT 200")) {
            $assistencias_res['erro'] = 'ERRO ao buscar Assistencias!';
        } else {
            while ($row = $result->fetch_assoc()) {
                $ids_cidadaos[] = $row['id_cidadao'];
            }
        }

        //Contar id de cidadãos com assistencias ativas
        $array_ids = [];
        foreach ($ids_cidadaos as $id_cidadao) {
            if (!in_array($id_cidadao, $array_ids)) {
                $array_ids[] = $id_cidadao;
            };
        }

        $assistencias_res['ids_cidadaos'] = $array_ids;

        $db->connClose();

        return $assistencias_res;
    }

    //Todos os Cidadãos que já foram ou ainda estão sendo assistido
    public function todosCidadaosAssistidos()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $assistencias_res = ['erro' => '', 'ids_cidadaos' => ''];

        $ids_cidadaos = [];

        if ($mysqli->connect_error) {
            $assistencias_res['erro'] = "Connection failed: " . $mysqli->connect_error;
        }

        if (!$result = $mysqli->query("SELECT id_cidadao FROM assistencias ORDER BY created_at DESC LIMIT 200")) {
            $assistencias_res['erro'] = 'ERRO ao buscar Assistencias!';
        } else {
            while ($row = $result->fetch_assoc()) {
                $ids_cidadaos[] = $row['id_cidadao'];
            }
        }

        //Contar id de cidadãos com assistencias ativas
        $array_ids = [];
        foreach ($ids_cidadaos as $id_cidadao) {
            if (!in_array($id_cidadao, $array_ids)) {
                $array_ids[] = $id_cidadao;
            };
        }

        $assistencias_res['ids_cidadaos'] = $array_ids;

        $db->connClose();

        return $assistencias_res;
    }

    public function aniversariantesDia()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'aniversariantes' => ''];
        $aniversariantes = [];

        $query = "SELECT * FROM aniversariantes LIMIT 10000";

        if (!$result = mysqli_query($mysqli, $query)) {
            $res['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {

            while ($row = $result->fetch_assoc()) {

                $row['idade'] = Times::idade_anos($row['data_nascimento']);

                $dn = new DateTime($row['data_nascimento']);
                $row['data_nascimento'] = date_format($dn, 'd/m/Y');

                $aniversariantes[] = $row;
            }
        }

        $res['aniversariantes'] = $aniversariantes;

        $db->connClose();

        return $res;
    }

    //Retira um aniversariante da lista
    public function deleteAniversariante($id_cidadao) {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'num_rows' => ''];

        $query = "DELETE FROM aniversariantes WHERE id_cidadao=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id_cidadao)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $res['num_rows'] = mysqli_affected_rows($mysqli);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;

    }

    public function countAniversariantes()
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'count' => ''];

        if (!$mysqli) {
            $res['erro'] = "Connection failed: " . mysqli_connect_error();
        } else {
            $query = "SELECT COUNT(*) AS quant FROM aniversariantes WHERE mensagem = 'Enviar'";
            $result = mysqli_query($mysqli, $query);
            $row = mysqli_fetch_object($result);

            $res['count'] = $row->quant;
        }

        $db->connClose();

        return $res;
    }
}
