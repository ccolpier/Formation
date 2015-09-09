<?php

namespace Model;

use Entity\News;
use \Others\DateTimeFram;

class NewsManagerPDO extends NewsManager{
    protected function add(News $news)
    {
        $query = 'INSERT INTO news (auteur, titre, contenu, dateAjout, dateModif) VALUES (:auteur, :titre, :contenu, GETUTCDATE(), GETUTCDATE())';
        $requete = $this->dao->prepare($query);
        $requete->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);
        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());

        $requete->execute();
        $news->setId($this->dao->lastInsertId());
    }

    protected function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);
        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function getList($debut = -1, $limite = -1){
        $query = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id BETWEEN '.(int)$debut.' AND '.(int)$limite;

        // Solution. Deuxième ligne nécessaire pour bind les résultats de la requête dans un type d'objets PHP
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
        $requete->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);

        $listeNews = $requete->fetchAll();

        // Solution. Le but est d'utiliser le type DateTime de PHP pour la gestion de dates
        /** @var $news News*/
        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new DateTimeFram($news->dateAjout(), new \DateTimeZone("UTC")));
            $news->setDateModif(new DateTimeFram($news->dateModif(), new \DateTimeZone("UTC")));
        }

        return $listeNews;
    }

    public function getUnique($id)
    {
        $query = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = '.(int)$id;
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
        $requete->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);

        //Solution
        /** @var $news News*/
        if ($news = $requete->fetch())
        {
            $news->setDateAjout(new DateTimeFram($news->dateAjout(), new \DateTimeZone("UTC")));
            $news->setDateModif(new DateTimeFram($news->dateModif(), new \DateTimeZone("UTC")));
        }

        return $news;
    }

    public function count(){
        $query = 'SELECT COUNT(id) FROM news';
        $result = $this->dao->query($query);
        $result->setAttribute(\PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);
        return $result->fetchColumn(0); // Renvoie le résultat de la premire colonne de la première ligne => càd le count
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
    }
}