<?php

namespace OCFram;

class BackController extends ApplicationComponent{
    protected $action = '';
    protected $module = '';
    protected $page = NULL;
    protected $view = '';
    protected $managers = NULL;

    public function __construct(Application $app, $module, $action){
        parent::__construct($app);

        $this->setModule($module);
        $this->setAction($action);
        $this->page = new Page($app);
        $this->setView($action);
        $this->managers = new Managers('PDO', PDOFactory::getSQLServerConnection());
    }

    public function execute(){
        $to_execute = 'execute'.ucfirst(strtolower($this->action));
        if(is_callable([$this, $to_execute])){
            $this->$to_execute($this->app()->HTTPRequest());
        }
    }

    public function page(){
        return $this->page;
    }

    public function setModule($module){
        if(is_string(($module))){
            $this->module = $module;
        }
    }

    public function setAction($action){
        if(is_string(($action))){
            $this->action = $action;
        }
    }

    public function setView($view){
        if(is_string(($view))){
            $this->view = $view;
            $this->page->setContentFile(__DIR__.'/../../App/'.$this->app->name().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
        }
    }

    public function redirect404(){
        $this->page = new Page($this->app());
        $this->page->setContentFile(__DIR__.'/../../Errors/404.html');
        $this->page->addHeader('HTTP/1.0 404 Not Found');
        $this->page->send();
    }
}