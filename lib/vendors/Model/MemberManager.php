<?php

namespace Model;
use \Entity\Member;

class MemberManager extends \OCFram\Manager{

    abstract protected function add(Comment $comment);

    public function save(Comment $comment)
    {
        if ($comment->isValid())
        {
            $comment->isNew() ? $this->add($comment) : $this->modify($comment);
        }
        else
        {
            throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
        }
    }

    abstract public function getListOf($news);

    abstract protected function modify(Comment $comment);

    abstract public function get($id);

    abstract public function delete($id);

    abstract public function deleteFromNews($news);
}