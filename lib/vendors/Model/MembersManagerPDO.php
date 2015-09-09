<?php

namespace Model;
use \Entity\Member;
use \Others\DateTimeFram;

class MembersManagerPDO extends MembersManager{
    protected function add(Member $member){
        $query = 'INSERT INTO members(nickname, password, firstname, lastname, dateofbirth, dateofregister, photo, biography) VALUES (:nickname, :password, :firstname, :lastname, :dateofbirth, GETUTCDATE(), :photo, :biography)';
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->prepare($query);

        $prepare->bindValue(':nickname', $member->nickname());
        $prepare->bindValue(':password', $member->password());
        $prepare->bindValue(':firstname', $member->firstname());
        $prepare->bindValue(':lastname', $member->lastname());
        $prepare->bindValue(':dateofbirth', $member->dateofbirth());
        $prepare->bindValue(':photo', $member->photo());
        $prepare->bindValue(':biography', $member->biography());

        $prepare->execute();

        $member->setId($this->dao->lastInsertId());
    }

    public function getList($debut = -1, $limite = -1){
        $query = 'SELECT id, nickname, password, firstname, lastname, dateofbirth, dateofregister, photo, biography FROM members WHERE id BETWEEN '.(int)$debut.' AND '.(int)$limite;

        /** @var $requete \PDOStatement*/
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Members');
        $listeMembers = $requete->fetchAll();

        /** @var $member Member*/
        foreach ($listeMembers as $member)
        {
            $member->setDateofbirth(new DateTimeFram($member->dateofbirth(), new \DateTimeZone("UTC")));
            $member->setDateofregister(new DateTimeFram($member->dateofregister(), new \DateTimeZone("UTC")));
        }

        return $listeMembers;
    }

    protected function modify(Member $member){
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->prepare('UPDATE members SET nickname = :nickname, password = :password, firstname = :firstname, lastname = :lastname,  dateofbirth = :dateofbirth, photo = :photo, biography = :biography WHERE id = :id');

        $prepare->bindValue(':nickname', $member->nickname());
        $prepare->bindValue(':password', $member->password());
        $prepare->bindValue(':firstname', $member->firstname());
        $prepare->bindValue(':lastname', $member->lastname());
        $prepare->bindValue(':dateofbirth', $member->dateofbirth());
        $prepare->bindValue(':photo', $member->photo());
        $prepare->bindValue(':biography', $member->biography());

        $prepare->execute();
    }

    public function getIdByName($nickname){
        $member = $this->getUniqueByName($nickname);
        return $member->id();
    }

    public function getUnique($id){
        $query = 'SELECT id, nickname, password, firstname, lastname, dateofbirth, dateofregister, photo, biography FROM members WHERE id = '.(int)$id;
        /** @var $requete \PDOStatement*/
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        /** @var $member Member*/
        if ($member = $requete->fetch())
        {
            $member->setDateofbirth(new DateTimeFram($member->dateofbirth(), new \DateTimeZone("UTC")));
            $member->setDateofregister(new DateTimeFram($member->dateofregister(), new \DateTimeZone("UTC")));
        }

        return $member;
    }

    public function getUniqueByName($nickname){
        $query = 'SELECT id, nickname, password, firstname, lastname, dateofbirth, dateofregister, photo, biography FROM members WHERE nickname = :nickname';
        /** @var $requete \PDOStatement*/
        $requete = $this->dao->prepare($query);
        $requete->bindValue(':nickname', $nickname);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');
        $requete->execute();

        /** @var $member Member*/
        if($member = $requete->fetch())
        {
            $member->setDateofbirth(new DateTimeFram($member->dateofbirth(), new \DateTimeZone("UTC")));
            $member->setDateofregister(new DateTimeFram($member->dateofregister(), new \DateTimeZone("UTC")));
        }
        return $member;
    }

    public function nicknameAlreadyTaken($nickname){
        return !empty($this->getUniqueByName($nickname));
    }

    public function delete($id){
        $this->dao->exec('DELETE FROM members WHERE id = '.(int) $id);
    }
}