<?php

namespace App\FrontEnd;

class FrontEndApplication extends \OCFram\Application {
    public function __construct(){
        parent::__construct();
    }

    public function run(){
        $controller = $this->getController();
        $controller->execute();

        $this->HTTPResponse()->setPage($controller->page());
        $this->HTTPResponse()->send();
    }
}