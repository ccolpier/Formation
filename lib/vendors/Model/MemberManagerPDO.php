<?php

namespace Model;
use \Entity\Member;

class MemberManagerPDO extends MemberManager{
    protected function add(Member $member){
        $query = 'INSERT INTO members(nickname, firstname, lastname, dateofbirth, dateofregister, photo, biography) VALUES (:nickname, :firstname, :lastname, :dateofbirth, :dateofregister, :photo, :biography)';
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->prepare($query);

        $prepare->bindValue(':nickname', $member->nickname());
        $prepare->bindValue(':firstname', $member->firstname());
        $prepare->bindValue(':lastname', $member->lastname());
        $prepare->bindValue(':dateofbirth', $member->dateofbirth());
        $prepare->bindValue(':dateofregister', $member->dateofregister());
        $prepare->bindValue(':photo', $member->photo());
        $prepare->bindValue(':biography', $member->biography());

        $prepare->execute();

        $member->setId($this->dao->lastInsertId());
    }

    public function getList($debut = -1, $limite = -1){
        $query = 'SELECT nickname, firstname, lastname, dateofbirth, dateofregister, photo, biography FROM members WHERE id BETWEEN '.(int)$debut.' AND '.(int)$limite;

        /** @var $requete \PDOStatement*/
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Members');
        $listeMembers = $requete->fetchAll();

        /** @var $member Member*/
        foreach ($listeMembers as $member)
        {
            $member->setDateofbirth(new \DateTime($member->dateofbirth(), new \DateTimeZone("UTC")));
            $member->setDateofregister(new \DateTime($member->dateofregister(), new \DateTimeZone("UTC")));
        }

        return $listeMembers;
    }

    protected function modify(Member $membre){
        /** @var $prepare \PDOStatement*/
        $prepare = $this->dao->prepare('UPDATE members SET nickname = :nickname, firstname = :firstname, lastname = :lastname,  dateofbirth = :dateofbirth, photo = :photo, biography = :biography WHERE id = :id');

        $prepare->bindValue(':nickname', $member->nickname());
        $prepare->bindValue(':firstname', $member->firstname());
        $prepare->bindValue(':lastname', $member->lastname());
        $prepare->bindValue(':dateofbirth', $member->dateofbirth());
        $prepare->bindValue(':photo', $member->photo());
        $prepare->bindValue(':biography', $member->biography());

        $prepare->execute();
    }

    public function getUnique($id){
        $query = 'SELECT nickname, firstname, lastname, dateofbirth, dateofregister, photo, biography FROM members WHERE id = '.(int)$id;
        /** @var $requete \PDOStatement*/
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        /** @var $member Member*/
        if ($member = $requete->fetch())
        {
            $member->setDateofbirth(new \DateTime($member->dateofbirth(), new \DateTimeZone("UTC")));
            $member->setDateofregister(new \DateTime($member->dateofregister(), new \DateTimeZone("UTC")));
        }

        return $member;
    }

    public function delete($id){
        $this->dao->exec('DELETE FROM members WHERE id = '.(int) $id);
    }
}