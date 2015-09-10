<?php

namespace Model;
use \Entity\Member;

abstract class MembersManager extends \OCFram\Manager{

    abstract protected function add(Member $member);

    public function save(Member $member)
    {
        if ($member->isValid())
        {
            $member->isNew() ? $this->add($member) : $this->modify($member);
        }
        else
        {
            throw new \RuntimeException('Le member doit être validé pour être enregistré');
        }
    }

    abstract public function getList($debut = -1, $limite = -1);

    abstract public function getListByName($nickname);

    abstract public function modify(Member $member);

    abstract public function getIdByName($nickname);

    abstract public function getUnique($id);

    abstract public function getUniqueByName($nickname);

    abstract public function nicknameAlreadyTaken($nickname);

    abstract public function delete($id);
}