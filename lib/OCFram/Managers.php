<?php

namespace OCFram;

class Managers{
    protected $api = NULL;
    protected $dao = NULL;
    protected $managers = array();

    public function __construct($api, $dao){
        $this->api = $api;
        $this->dao = $dao;
    }

    public function getManagerOf($module){
        if (!isset($this->managers[$module]))
        {
            $manager = '\\Model\\'.$module.'Manager'.$this->api;
            $this->managers[$module] = new $manager($this->dao);
            return $this->managers[$module];
        }
        else {
            return $this->managers[$module];
        }
    }
}