<?php

namespace Model;

class NewsManagerPDO extends NewsManager{
    protected function add(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO news VALUES auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = GETUTCDATE(), dateModif = GETUTCDATE()');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());

        $requete->execute();
    }

    protected function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

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

        $listeNews = $requete->fetchAll();

        // Solution. Le but est d'utiliser le type DateTime de PHP pour la gestion de dates
        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        return $listeNews;
    }

    public function getUnique($id)
    {
        $query = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = '.$id;
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        //Solution
        if ($news = $requete->fetch())
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        return $news;
    }

    public function count(){
        $query = 'SELECT COUNT(id) FROM news';
        $result = $this->dao->query($query);
        return $result->fetchColumn(0); // Renvoie le résultat de la premire colonne de la première ligne => càd le count
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
    }
}