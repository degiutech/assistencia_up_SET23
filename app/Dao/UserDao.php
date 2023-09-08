<?php

class UserDao
{

    private $db;
    private $user;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Registra um usuário sem a senha
     * return último id inserido
     */
    public function create(UserModel $user)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        if (!($stmt = $mysqli->prepare("INSERT INTO users (nome, email, cep, logradouro, numero, complemento, 
        bairro, cidade, uf, acesso) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
        	return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        
        $nome = $user->getName_user();
        $email = $user->getEmail();
        $cep = $user->getCep();
        $logradouro = $user->getLogradouro();
        $numero = $user->getNumero();
        $complemento = $user->getComplemento();
        $bairro = $user->getBairro();
        $cidade = $user->getCidade();
        $uf = $user->getUf();
        $acesso = 'nenhum';
        

        if (!$stmt->bind_param("ssssssssss", $nome, $email, $cep, $logradouro, $numero, $complemento, $bairro, 
        $cidade, $uf, $acesso)) {
            return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            return mysqli_insert_id($mysqli);
        }
    }

    /**
     * Função para registrar a senha do usuário - seja na troca ou do registro normal
     * 
     * Redireciona para a página de login
     * 
     */
    public function passRegister(UserModel $user)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        if (!($stmt = $mysqli->prepare("UPDATE users SET senha = ? WHERE id = ?"))) {
            return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        $id = $user->getId();
        $senha = $user->getPass();

        if (!$stmt->bind_param("si", $senha, $id)) {
            return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            //return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            //die("Erro ao registrar a senha no banco de dados");
        } else {
            Sessao::mensagem('user', 'Registros realizados com sucesso!');
            Url::redirect('users/login');
        }

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
                return true;
            } else {
                return false;
            }
        }





        // $this->db->query("SELECT email FROM users WHERE email = :e");
        // $this->db->bind(":e", $email);

        // if ($this->db->resultado()):
        //     return true;
        // else:
        //     return false;
        // endif;
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

        if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?"))) {
            return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("s", $email)) {
            return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            //return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        } else {
            $result = $stmt->get_result(); // get the mysqli result
            $row = $result->fetch_assoc();

            if ($row['senha'] != '') :

                if (password_verify($pass, $row['senha'])) :
                    return $row;
                else :
                    return false;
                endif;

            endif;
        }




        // $this->db->query("SELECT * FROM users WHERE email = :e");
        // $this->db->bind(":e", $email);

        // if ($this->db->resultado()):
        //     $resultado = $this->db->resultado();

        //     if ($resultado->pass != ''):
        //         if (password_verify($pass, $resultado->pass)):
        //             return $resultado;
        //         else:
        //             return false;
        //         endif;
        //     else:
        //         return 'pass-empty';
        //     endif;

        // else:
        //     return false;
        // endif;
    }

    public function readUserById($id)
    {

        $db = new Database();
        $mysqli = $db->getConection();

        if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE id = :id"))) {
            return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }

        if (!$stmt->bind_param("i", $id)) {
            return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!$stmt->execute()) {
            //return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        } else {
            $result = $stmt->get_result(); // get the mysqli result
            $row = $result->fetch_assoc();

            return $row;
            
        }




        // $this->db->query("SELECT * FROM users WHERE id = :id");
        // $this->db->bind('id', $id);

        // return $this->db->resultado();
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
