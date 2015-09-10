<?php

namespace OCFram;

class Page extends ApplicationComponent{
    protected $layoutFile;
    protected $contentFile;
    protected $vars = array();

    public function __construct(Application $app){
        parent::__construct($app);
        $this->setLayoutFile( __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php');
    }

    public function addVar($var, $value){
        if(is_string($var)){
            $this->vars[$var] = $value;
        }
    }

    public function getGeneratedPage(){
        if (file_exists($this->contentFile))
        {
            $this->App()->httpResponse()->addHeader('Content-Type: text/html; charset=ISO-8859-1');

            $user = $this->app->user();
            extract($this->vars);

            ob_start();
            require $this->contentFile;
            $content = ob_get_clean();

            if(!empty($this->layoutFile)) {
                ob_start();
                require $this->layoutFile;
                return ob_get_clean();
            }
        }
    }

    public function setContentFile($contentFile){
        if(is_string($contentFile)){
            $this->contentFile = $contentFile;
        }
    }

    public function setLayoutFIle($layoutFile){
        if(is_string($layoutFile)){
            $this->layoutFile = $layoutFile;
        }
    }
}