<?php

class DatabaseDg {

    private $host = DBDG['HOST'];
    private $username = DBDG['USER'];
    private $password = DBDG['PASS'];
    private $database = DBDG['DATABASE'];
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




