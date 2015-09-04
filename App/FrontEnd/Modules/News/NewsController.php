<?php

namespace App\FrontEnd\Modules\News;

class NewsController extends \OCFram\BackController {
    public function executeIndex(\OCFram\HTTPRequest $request){
        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');
        $this->page->addVar('title', 'Liste des '.$nombreNews.' derni�res news');

        $manager = $this->managers->getManagerOf('News');
        $listeNews = $manager->getList(0, $nombreNews);
        foreach ($listeNews as $news)
        {
            if (strlen($news->contenu()) > $nombreCaracteres)
            {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);
                // Donn� dans la solution, cette ligne permet de remplacer le dernier mot par 3 petits points
                // si la longeur du texte est sup�rieure au maximum de caract�res
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $news->setContenu($debut);
            }
        }
        $this->page->addVar('listeNews', $listeNews);
    }
}