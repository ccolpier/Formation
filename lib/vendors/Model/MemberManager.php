<?php

namespace Model;
use \Entity\Member;

class MemberManager extends \OCFram\Manager{

    abstract protected function add(Member $member);

    public function save(Member $member)
    {
        if ($member->isValid())
        {
            $member->isNew() ? $this->add($member) : $this->modify($member);
        }
        else
        {
            throw new \RuntimeException('Le member doit �tre valid� pour �tre enregistr�');
        }
    }

    abstract public function getList($debut = -1, $limite = -1);

    abstract protected function modify(Member $membre);

    abstract public function get($id);

    abstract public function delete($id);
}