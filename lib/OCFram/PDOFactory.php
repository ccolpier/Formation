<?php

namespace OCFram;

class PDOFactory{
    public static function getSQLServerConnection(){
        $serverName = 'ACTARUS-ASP-SQL\JOON,1443';
        $database = 'test';
        $username = 'sa';
        $password = 'sa';
        $db = new \PDO('sqlsrv:Server='.$serverName.'; Database='.$database, $username, $password);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::ATTR_PERSISTENT , true);
        $db->setAttribute(\PDO::ATTR_CURSOR , \PDO::CURSOR_SCROLL);
        return $db;
    }
}