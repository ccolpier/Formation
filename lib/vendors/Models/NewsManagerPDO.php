<?php

namespace Models;

class NewsManagerPDO extends NewsManager{
    public function getList($debut = -1, $limite = -1){
        $query = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id BETWEEN '.$d�but.' AND '.$limite;

        // Solution. Deuxi�me ligne n�cessaire pour bind les r�sultats de la requ�te dans un type d'objets PHP
        $requete = $this->dao->query($query);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();

        // Solution. Le but est d'utiliser le type DateTime de PHP pour la gestion de dates
        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }
    }
}