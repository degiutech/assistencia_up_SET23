<?php

class Database {

    private $host = DB['HOST'];
    private $username = DB['USER'];
    private $password = DB['PASS'];
    private $database = DB['DATABASE'];
    private $conexao = null;

    public function __construct()
    {
        $this->connect();
    }

    public function getConection()
    {
        
        $mysqli = $this->conexao;

        mysqli_set_charset($mysqli, "utf8");
		
		if ($mysqli->connect_errno) {
			return "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

        return $mysqli;
    }

    private function connect()
    {
        $this->conexao = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );
    }

    public function connClose()
    {
        return $this->conexao->close();
    }

}




