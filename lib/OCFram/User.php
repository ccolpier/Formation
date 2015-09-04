<?php

namespace OCFram;

session_start();

class User extends ApplicationComponent{
    public function getAttribute($attr){
        if(isset($_SESSION[$attr])){
            return $_SESSION[$attr];
        }
    }

    public function getFlash(){
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function hasFlash(){
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated(){
        if(isset($_SESSION['autheticated']) && $_SESSION['autheticated'] === true){
            return true;
        }
        return false;
    }

    public function setAttribute($attr, $value){
        if(isset($attr)){
            $_SESSION[$attr] = $value;
        }
    }

    public function setAuthenticated($authenticated = true){
        if(is_bool($authenticated)){
            $_SESSION['autheticated'] = $authenticated;
        }
    }

    public function setFlash($value){
        $_SESSION['flash'] = $value;
    }
}