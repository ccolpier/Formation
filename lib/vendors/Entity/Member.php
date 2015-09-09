<?php

namespace Entity;

use \Others\DateTimeFram;

class Member extends \OCFram\Entity {
    protected $nickname,
        $password,
        $firstname,
        $lastname,
        $dateofbirth,
        $dateofregister,
        $photo,
        $biography;

    //Codes d'erreurs
    const NICKNAME_INVALID = 1;
    const PASSWORD_INVALID = 2;
    const FIRSTNAME_INVALID = 3;
    const LASTNAME_INVALID = 4;
    const PHOTO_INVALID = 5;
    const BIOGRAPHY_INVALID = 6;

    public function isValid(){
        return !(empty($this->nickname) || empty($this->firstname) || empty($this->lastname) || empty($this->dateofbirth) || empty($this->dateofregister));
    }

    public function setNickname($nickname){
        if(is_string($nickname) && !empty($nickname)){
            $this->nickname = $nickname;
        }
        else {
            $this->erreurs[] = self::NICKNAME_INVALID;
        }
    }

    public function setPassword($password){
        if(is_string($password) && !empty($password)){
            $this->password = $password;
        }
        else {
            $this->erreurs[] = self::PASSWORD_INVALID;
        }
    }

    public function setFirstname($firstname){
        if(is_string($firstname) && !empty($firstname)){
            $this->firstname = $firstname;
        }
        else {
            $this->erreurs[] = self::FIRSTNAME_INVALID;
        }
    }

    public function setLastname($lastname){
        if(is_string($lastname) && !empty($lastname)){
            $this->lastname = $lastname;
        }
        else {
            $this->erreurs[] = self::LASTNAME_INVALID;
        }
    }

    public function setDateofbirth(DateTimeFram $dateofbirth){
        $this->dateofbirth = $dateofbirth;
    }

    public function setDateofregister(DateTimeFram $dateofregister){
        $this->dateofregister = $dateofregister;
    }

    public function setPhoto($photo){
        if(is_string($photo) && !empty($photo)){
            $this->photo = $photo;
        }
        else {
            $this->erreurs[] = self::PHOTO_INVALID;
        }
    }

    public function setBiography($biography){
        if(is_string($biography) && !empty($biography)){
            $this->photo = $biography;
        }
        else {
            $this->erreurs[] = self::BIOGRAPHY_INVALID;
        }
    }

    public function nickname(){
        return $this->nickname;
    }

    public function password() {
        return $this->password;
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