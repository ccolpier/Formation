<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Comment;

abstract class CommentsManager extends Manager
{
    /**
     * M�thode permettant d'ajouter un commentaire
     * @param $comment Le commentaire � ajouter
     * @return void
     */
    abstract protected function add(Comment $comment);

    /**
     * M�thode permettant d'enregistrer un commentaire.
     * @param $comment Le commentaire � enregistrer
     * @return void
     */
    public function save(Comment $comment)
    {
        if ($comment->isValid())
        {
            $comment->isNew() ? $this->add($comment) : $this->modify($comment);
        }
        else
        {
            throw new \RuntimeException('Le commentaire doit �tre valid� pour �tre enregistr�');
        }
    }

    /**
     * M�thode permettant de r�cup�rer une liste de commentaires.
     * @param $news La news sur laquelle on veut r�cup�rer les commentaires
     * @return array
     */
    abstract public function getListOf($news);

    /**
     * M�thode permettant de modifier un commentaire.
     * @param $comment Le commentaire � modifier
     * @return void
     */
    abstract protected function modify(Comment $comment);

    /**
     * M�thode permettant d'obtenir un commentaire sp�cifique.
     * @param $id L'identifiant du commentaire
     * @return Comment
     */
    abstract public function get($id);

    /**
     * M�thode permettant de supprimer un commentaire.
     * @param $id L'identifiant du commentaire � supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * M�thode permettant de supprimer tous les commentaires li�s � une news
     * @param $news L'identifiant de la news dont les commentaires doivent �tre supprim�s
     * @return void
     */
    abstract public function deleteFromNews($news);

}