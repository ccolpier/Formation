<?php

namespace Entity;

class Member extends \OCFram\Entity {
    protected $nickname,
        $firstname,
        $lastname,
        $dateofbirth,
        $dateofregister,
        $photo,
        $biography;

    public function isValid(){
        return !(empty($this->firstname) || empty($this->lastname) || empty($this->dateofbirth) || empty($this->dateofregister));
    }

}