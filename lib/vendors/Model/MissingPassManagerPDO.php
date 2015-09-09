<?php

namespace Model;
use \Entity\MissingPass;
use \Entity\Member;

abstract class MissingPassManagerPDO extends MissingPassManager{
    public function save(MissingPass $missingPass)
    {
        if ($missingPass->isValid())
        {
            $missingPass->isNew() ? $this->add($missingPass) : $this->modify($missingPass);
        }
        else
        {
            throw new \RuntimeException('Le member doit être validé pour être enregistré');
        }
    }


    public function add(Member $member){
        if(!empty($this->get($member))){
            throw new \RuntimeException('Ce membre attend déjà une demande de renouvellement de mot de passe.');
        }
        $query = 'INSERT INTO missingPass(member, code) VALUES(:member, :code)';
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->prepare($query);
        $prepare->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);
        $prepare->bindValue(':member', $member->id(), \PDO::PARAM_INT);
        $prepare->bindValue(':code', $this->generate());
        $prepare->execute();

        $missingPass = $this->get($member);
        $missingPass->setId($this->dao->lastInsertId());

        return $missingPass;
    }

    public function get(Member $member){
        $query = 'SELECT id, member, code FROM missingPass WHERE member = '.(int)$member->id();
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->query($query);
        $prepare->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\MissingPass');
        $prepare->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);

        return $prepare->fetch();
    }

    public function refresh(Member $member){
        // Si il y a déjà un missing, on le détruit
        if(!empty($this->get($member))){
            $this->delete($member);
        }
        // On retourne un nouveau missing (donc un nouveau code)
        return $this->add($member);
    }

    public function delete(Member $member){
        $this->dao->exec('DELETE FROM missingPass WHERE member = '.(int)$member->id());
    }
}