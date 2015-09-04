<?php

namespace OCFram;

abstract class Application {
    protected $httpRequest;
    protected $httpResponse;
    protected $name;

    public function __construct(){
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);
        $this->name = '';
    }

    public function run(){

    }

    public function HTTPRequest(){
        return $this->httpRequest;
    }

    public function HTTPResponse(){
        return $this->httpResponse;
    }

    public function name(){
        return $this->name;
    }
}