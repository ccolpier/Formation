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
        return !(empty($this->nickname()) || empty($this->firstname) || empty($this->lastname) || empty($this->dateofbirth) || empty($this->dateofregister));
    }

    public function setNickname(){

    }

    public function setFirstname(){

    }

    public function setLastname(){

    }

    public function setDateofbirth(){

    }

    public function setDateofregister(){

    }

    public function setPhoto(){

    }

    public function setBiography(){

    }

    public function nickname(){
        return $this->nickname;
    }

    public function firstname(){
        return $this->firstname;
    }

    public function lastname(){
        return $this->lastname;
    }

    public function dateofbirth(){
        return $this->dateofbirth;
    }

    public function dateofregister(){
        return $this->dateofregister;
    }

    public function photo(){
        return $this->photo;
    }

    public function biography(){
        return $this->biography;
    }
}