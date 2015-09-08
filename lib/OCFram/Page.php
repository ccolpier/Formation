<?php

namespace OCFram;

class Page extends ApplicationComponent{
    protected $contentFile;
    protected $vars = array();

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

            ob_start();
            require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
            return ob_get_clean();
        }
    }

    public function setContentFile($contentFile){
        if(is_string($contentFile)){
            $this->contentFile = $contentFile;
        }
    }
}