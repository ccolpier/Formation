<?php

namespace OCFram;

class Route {
    protected $action;
    protected $module;
    protected $url;
    protected $varsNames = array();
    protected $vars = array();

    public function  __construct($url, $module, $action, array $varsNames){
        $this->setAction($action);
        $this->setModule($module);
        $this->setUrl($url);
        $this->setVarsNames($varsNames);
    }

    public function hasVars(){
        return !empty($varsNames);
    }

    public function match($url){
        return preg_match('^'.$this->url.'$', $url);
    }

    public function setAction($action){
        if(is_string($action)){
            $this->action = $action;
        }
    }

    public function setModule($module){
        if(is_string($module)){
            $this->module = $module;
        }
    }

    public function setUrl($url){
        if(is_string($url)){
            $this->url = $url;
        }
    }

    public function setVarsNames(array $varsNames){
        $this->varsNames = $varsNames;
    }

    public function setVars(array $vars){
        $this->vars = $vars;
    }

    public function action(){
        return $this->action;
    }

    public function module(){
        return $this->module;
    }

    public function url(){
        return $this->url;
    }

    public function varsNames(){
        return $this->varsNames;
    }

    public function vars(){
        return $this->vars;
    }
}