<?php

namespace OCFram;

class PDOFactory{
    public static function getSQLServerConnection(){
        $serverName = 'ACTARUS-ASP-SQL\JOON';
        $database = 'test';
        $username = 'sa';
        $password = 'sa';
        try {
            $db = new \PDO("sqlsrv:Server=$serverName ; Database=$database", $username, $password);
        } catch(\PDOException $e){
            die($e->getMessage());
        }
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}