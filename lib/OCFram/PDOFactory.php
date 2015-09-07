<?php

namespace OCFram;

class PDOFactory{
    public static function getSQLServerConnection(){
        $server_name = 'ACTARUS-ASP-SQL\JOON, 1443';
        $connection_infos = array( "Database"=>"madnetix_zero", "UID"=>"sa", "PWD"=>"sa");
        $db = sqlsrv_connect($server_name, $connection_infos);
        return $db;
    }
}