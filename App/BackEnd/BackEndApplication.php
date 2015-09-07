<?php

namespace App\BackEnd;

class BackEndApplication extends \OCFram\Application {
    public function __construct(){
        parent::__construct();
        $this->name = 'BackEnd';
    }

    public function run(){
        if($this->user()->isAuthenticated()){
            $controller = $this->getController();
        }
        else {
            //Solution
            $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }
        $controller->execute();

        $this->HTTPResponse()->setPage($controller->page());
        $this->HTTPResponse()->send();
    }
}