<?php

namespace App\FrontEnd\Modules\FormValidation;

use OCFram\HTTPRequest;

class FormValidationController extends \OCFram\BackController{
    public function __construct(\OCFram\Application $app, $module, $action){
        parent::__construct($app, $module, $action);
        $this->page->setContentFile('');
        $this->page->setLayoutFIle('');
    }

    public function executeValidate(HTTPRequest $request){
        if($request->method() != 'POST'){
            return;
        }
        $res = '';
        foreach($_POST as $field => $value){
            //Selon la valeur de field dans POST on va appeler la fonction privée qu'il faut
            $to_call = 'validate'.ucfirst(strtolower($field));
            if(is_callable([$this, $to_call])){
                $message = $this->$to_call($value);
                $res .= ($message.PHP_EOL);
            }
        }
        echo $res;
    }

    //Exemple
    protected function validatePassword($password){
        if(!is_string($password)){
            return 'Le mot de passe doit être une chaîne de caractères.';
        }
        if(strlen($password) < 5){
            return 'Le mot de passe doit faire au moins 5 caractères.';
        }
        return '';
    }

    protected function validateNickname($nickname){
        if(!is_string($nickname)){
            return 'Le pseudo doit être une chaîne de caractères.';
        }
        if(strlen($nickname) < 5){
            return 'Le pseudo doit faire au moins 5 caractères.';
        }
        return '';
    }
}