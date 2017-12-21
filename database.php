<?php

/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 20.12.2017
 * Time: 09:06
 */
class database
{

    private $host;
    private $user;
    private $pass;
    private $name;

    private $db;
    private $stmt;

    public function __construct($host, $user, $pass, $name)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->name = $name;

        $this->db = new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user, $this->pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    private function query($sqlQuery)
    {
        $this->stmt = $this->db->prepare($sqlQuery);
    }

    private function execute($param = null){
        if ($param == null) {
            return $this->stmt->execute();
        }
        else {
            return $this->stmt->execute([":param" => $param]);
        }
    }

    public function result($param = null)
    {

        $this->execute($param);
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

}