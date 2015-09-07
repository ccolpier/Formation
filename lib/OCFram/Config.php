<?php

namespace OCFram;

class Config extends ApplicationComponent{
    protected $vars = array();

    public function get($var){
        if(!empty($var)){
            $xml = new \DOMDocument;
            $xml->load(__DIR__.'/../../App/'.$this->app()->name().'/Config/app.xml');
            $configs = $xml->getElementsByTagName('define');
            foreach($configs as $config){
                $this->var[$config->getAttribute('var')] = $config->getAttribute('value');
            }
        }
        return isset($this->vars[$var]) ? $this->vars[$var] : NULL;
    }
}