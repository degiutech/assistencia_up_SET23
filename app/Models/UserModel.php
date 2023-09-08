<?php

class UserModel
{

    public function __construct()
    {
    }



    /**
     * Registra um usuário sem a senha e sem endereço
     * return último id inserido
     */
    public function create($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("INSERT INTO users (nome, email, celular, acesso, id_coordenadoria, bloqueio, cep, 
        logradouro, numero, complemento, bairro, cidade, uf, ibge, local_trabalho, cidade_trabalho, uf_trabalho, senha) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $nome = $dados['nome'];
        $email = $dados['email'];
        $celular = $dados['celular'];
        $acesso = $dados['acesso'];
        $id_coordenadoria = $dados['id_coordenadoria'];
        $bloqueio = 'Desbloqueado';
        $cep = $dados['cep'];
        $logradouro = $dados['logradouro'];
        $numero = $dados['numero'];
        $complemento = $dados['complemento'];
        $bairro = $dados['bairro'];
        $cidade = $dados['cidade'];
        $uf = $dados['uf'];
        $ibge = $dados['ibge'];
        $local_trabalho = $dados['local_trabalho'];
        $cidade_trabalho = $dados['cidade_trabalho'];
        $uf_trabalho = $dados['uf_trabalho'];
        $pass = password_hash($dados['pass'], PASSWORD_DEFAULT);


        if (!$stmt->bind_param(
            "ssssisssssssssssss",
            $nome,
            $email,
            $celular,
            $acesso,
            $id_coordenadoria,
            $bloqueio,
            $cep,
            $logradouro,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $uf,
            $ibge,
            $local_trabalho,
            $cidade_trabalho,
            $uf_trabalho,
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

    /**
     * Update endereço de usuário
     */
    public function updateEndereco($dados)
    {
        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE users SET cep=?, logradouro=?, numero=?, complemento=?, 
        bairro=?, cidade=?, uf=?, updated_at=?, ibge=?
        WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return $result;
        }

        $cep = $dados['cep'];
        $logradouro = $dados['logradouro'];
        $numero = $dados['numero'];
        $complemento = $dados['complemento'];
        $bairro = $dados['bairro'];
        $cidade = $dados['cidade'];
        $uf = $dados['uf'];
        $updated_at = date('Y-m-d H:i:s');
        $ibge = $dados['ibge'];
        $id = $dados['id'];


        if (!$stmt->bind_param("sssssssssi", $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $updated_at, $ibge, $id)) {
            $result['erro'] =  "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            return $result;
        }

        if (!$stmt->execute()) {
            $result['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result['affected_rows'] = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $result;
    }

    /**
     * Pega o endereco
     */
    public function getEndereco($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $resultado = ['erro' => '', 'endereco' => ''];

        if (!($stmt = $mysqli->prepare("SELECT cep, logradouro, numero, complemento, bairro, cidade, uf FROM users WHERE id=? "))) {
            $resultado['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            return $resultado;
        }

        if (!$stmt->bind_param("i", $id)) {
            $resultado['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            return $resultado;
        }

        if (!$stmt->execute()) {
            $resultado['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return $resultado;
        }

        $result = $stmt->get_result();

        $resultado['endereco'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $resultado;
    }

    /**
     * Função para checar se existe email
     * 
     * return boolean
     */
    public function checkEmail($email)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        if (!($stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?"))) {
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

    /**
     * Compara se email é o mesmo
     */
    public function comparaEmailId($email, $id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['erro' => '', 'result' => ''];

        if (!($stmt = $mysqli->prepare("SELECT email FROM users WHERE id=? AND email=?"))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("is", $id, $email)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            //return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();
        $res['result'] = mysqli_num_rows($result);


        mysqli_stmt_close($stmt);
        $db->connClose();

        return $res;
    }

    /**
     * Função para verificar se existe um usuário com email e senha e se existe uma senha, 
     * compara a senha com o hash e pode retornar os dados do usuário se tudo estiver correto
     * 
     * return array, boolean e string
     */
    public function checkLogin($email, $pass)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $resposta = ['id' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("SELECT senha, id FROM users WHERE email = ?"))) {
            $resposta['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $email)) {
            $resposta['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $resposta['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result
        $row = $result->fetch_assoc();

        if ($row['senha'] != '') :

            if (password_verify($pass, $row['senha'])) :
                $resposta['id'] = $row['id'];
            else :
                $resposta['erro'] = 'Senha não confere!';
            endif;

        endif;
        return $resposta;
    }

    public function readUserById($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $res = ['user' => '', 'erro' => ''];

        if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?"))) {
            $res['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $res['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $res['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result(); // get the mysqli result
        $row = $result->fetch_assoc();

        $res['user'] = $row;

        mysqli_stmt_close($stmt);
        $db->connClose();

        if ($row['id_coordenadoria'] != 0) {
            $coord = $this->getCoordenadoria($row['id_coordenadoria']);

            if ($coord['erro'] == '' && $coord['coord'] !== '') {
                $res['user']['nome_coordenadoria'] = $coord['coord']['nome'];
            } else {
                $res['user']['nome_coordenadoria'] = null;
            }
        }

        return $res;
    }

    public function getCoordenadoria($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $coord = ['erro' => '', 'coord' => ''];

        if (!($stmt = $mysqli->prepare("SELECT * FROM coordenadorias WHERE id = ?"))) {
            $coord['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $coord['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $coord['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $coord['coord'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $coord;
    }

    public function getUser()
    {
        $user = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],

        ];
        return $user;
    }

    public function meusDados()
    {

        $id = $_SESSION['user']['id'];

        $db = new Database();
        $mysqli = $db->getConection();

        $user = ['erro' => '', 'user' => ''];

        if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?"))) {
            $user['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            $user['erro'] = "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            $user['erro'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        $user['user'] = $result->fetch_assoc();

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $user;
    }

    public function updateMeusDados($dados)
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

    public function getHash($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $hash = ['erro' => '', 'hash' => ''];

        if (!($stmt = $mysqli->prepare("SELECT senha FROM users WHERE id = ?"))) {
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

        $hash['hash'] = $row['senha'];

        mysqli_stmt_close($stmt);
        $db->connClose();

        return $hash;
    }

    public function updateMinhaSenha($dados)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        $result = ['affected_rows' => '', 'erro' => ''];

        $query = "UPDATE users SET senha=? WHERE id=?";

        if (!($stmt = $mysqli->prepare($query))) {
            $result['erro'] = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $senha = password_hash($dados['pass'], PASSWORD_DEFAULT);
        $id = $dados['id'];


        if (!$stmt->bind_param(
            "si",
            $senha,
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
	
	//Todos os usuários sem restrição para CHAT
    public function allUsersChat() {
        if (!Sessao::estaLogado()) {
            exit('É preciso estar logado!');
        }

        $db = new Database();
        $mysqli = $db->getConection();

        $retorno = ['users' => '', 'erro' => ''];

        $query = "SELECT id, nome, acesso, id_coordenadoria, bloqueio FROM users WHERE acesso != 'Dev' AND bloqueio = 'Desbloqueado'";

        if (!$result = mysqli_query($mysqli, $query)) {
            $retorno['erro'] = 'ERRO: ' . mysqli_error($mysqli);
        } else {
            $retorno['users'] = $result->fetch_all(MYSQLI_ASSOC);
        }

        mysqli_close($mysqli);

        return $retorno;
    }

    //BUSCA

    // public function getUserByEmail($email)
    // {

    //     $this->db->query("SELECT * FROM users WHERE email = :e");
    //     $this->db->bind(':e', $email);
    //     $user = $this->db->resultado();

    //     return $user;
    // }

    // /**
    //  * Salva na tabela recovery_password
    //  * 
    //  * return url concatenada com o hash_reset
    //  */
    // public function saveRecovery($user)
    // {

    //     $hash_reset = bin2hex(openssl_random_pseudo_bytes(16) . $user->pass);

    //     $this->db->query("INSERT INTO recovery_pass(id_user, email, hash_reset) VALUES(:id_user, :email, :hash_reset)");
    //     $this->db->bind(':id_user', $user->id);
    //     $this->db->bind(':email', $user->email);
    //     $this->db->bind(':hash_reset', $hash_reset);

    //     if ($this->db->executa()) :
    //         $id_recovery = $this->db->ultimoIdInserido();

    //         if ($this->saveHashResetUser($hash_reset, $user->id) && $this->addProcedureRecoveryPass($id_recovery)) :
    //             return URL . '/users/recovery_pass/?key=' . $hash_reset;
    //         else :
    //             return false;
    //         endif;
    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * Adiciona procedure na tabela recovery_pass - 24 horas
    //  * 
    //  * return boolean
    //  */
    // public function addProcedureRecoveryPass($id_recovery)
    // {

    //     $expire_on = "expire_on" . $id_recovery;

    //     //1 minute  24 HOUR
    //     $this->db->query("CREATE EVENT IF NOT EXISTS $expire_on "
    //         . "ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 minute"
    //         . " DO "
    //         . "DELETE FROM recovery_pass WHERE id = :id");

    //     $this->db->bind(':id', $id_recovery);

    //     if ($this->db->executa()) :
    //         return true;
    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * Salva hash_reset na tabela de usuários
    //  * 
    //  * return boolean
    //  */
    // public function saveHashResetUser($hash_reset, $id_user)
    // {

    //     $this->db->query("UPDATE users SET hash_reset = :hash_reset WHERE id = :id_user");
    //     $this->db->bind(':hash_reset', $hash_reset);
    //     $this->db->bind(':id_user', $id_user);

    //     if ($this->db->executa()) :
    //         return true;
    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * altera a senha do usuário
    //  * 
    //  * return boolean
    //  */
    // public function recoveryPass($dados)
    // {

    //     $chek = $this->checkKeyRecovery($dados['key']);

    //     if ($chek) :

    //         $pass = password_hash($dados['pass'], PASSWORD_DEFAULT);

    //         $this->db->query("UPDATE users SET pass = :pass WHERE email = :email");
    //         $this->db->bind(':pass', $pass);
    //         $this->db->bind(':email', $dados['email']);

    //         if ($this->db->executa()) :
    //             if ($this->deleteRecoveryPass($dados['email'], $dados['key'])) :
    //                 return true;
    //             else :
    //                 return false;
    //             endif;
    //         else :
    //             return false;
    //         endif;

    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * Verifica o hash de recuperação de senha só tem 1
    //  * 
    //  * return boolean
    //  */
    // public function checkKeyRecovery($key_reset)
    // {

    //     $this->db->query("SELECT * FROM recovery_pass WHERE hash_reset = :key_reset");
    //     $this->db->bind(':key_reset', $key_reset);

    //     if ($this->db->executa()) :
    //         if ($this->db->totalResultados() != 1) :
    //             return false;
    //         else :
    //             return true;
    //         endif;
    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * Deleta a chave de recuperação de senha na tabela recovery_pass
    //  * 
    //  * return boolean
    //  */
    // public function deleteRecoveryPass($email, $key_reset)
    // {

    //     $this->db->query("DELETE FROM recovery_pass WHERE email = :email");
    //     $this->db->bind(':email', $email);

    //     if ($this->db->executa()) :
    //         if ($this->deleteHashResetUser($key_reset)) :
    //             return true;
    //         endif;
    //     else :
    //         return false;
    //     endif;
    // }

    // /**
    //  * Deleta o hash_reset do usuário na tabela users
    //  * 
    //  * return boolean
    //  */
    // public function deleteHashResetUser($hash_reset)
    // {

    //     $hr = '';

    //     $this->db->query("UPDATE users SET hash_reset = :hr WHERE hash_reset = :hash_reset");
    //     $this->db->bind(':hr', $hr);
    //     $this->db->bind(':hash_reset', $hash_reset);

    //     if ($this->db->executa()) :
    //         return true;
    //     else :
    //         return false;
    //     endif;
    // }
}
