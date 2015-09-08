<?php

namespace Model;
use \Entity\Member;

class MemberManagerPDO extends MemberManager{
    protected function add(Member $member){
        $query = 'INSERT INTO members()';
    }

    public function getList($debut = -1, $limite = -1){

    }

    protected function modify(Member $membre){

    }

    public function get($id){

    }

    public function delete($id){

    }
}