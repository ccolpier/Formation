<?php

namespace OCFram;

class PDOFactory{
    public static function getSQLServerConnection(){
        $db = new \PDO('mssql:host=ACTARUS-ASP-SQL\MSSQLSERVER2012),1433;dbname=bd_madnetix', 'usr_madnetix', 'pass_madnetix');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::ATTR_PERSISTENT , true);
        $db->setAttribute(\PDO::ATTR_CURSOR , \PDO::CURSOR_SCROLL);
        return $db;
    }
}