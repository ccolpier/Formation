<?php

namespace Model;

class NewsManagerPDO extends NewsManager{
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
}