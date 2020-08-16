<?php

namespace Login;

class Connect
{
    public $mysqli;

    function __construct()
    {
        $host="localhost";
        $user="root";
        $password="root";
        $dataBase="Login";

        $this->mysqli = new \MySQLi($host, $user, $password, $dataBase);
        $this->mysqli->set_charset('utf8');
        $this->mysqli->query("SET time_zone = '-3:00'");
    }

    public function query($query)
    {
        if(!$result = $this->mysqli->query($query)) return ["success"=>false,"error"=>$this->mysqli->error];

        return $result;
    }

}