<?php

namespace App\FrontEnd\Modules\News;

class NewsController extends \OCFram\BackController {
    public function executeIndex(\OCFram\HTTPRequest $request){
        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');
        $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');

        $manager = $this->managers->getManagerOf('News');
        $listeNews = $manager->getList(0, $nombreNews);
        foreach ($listeNews as $news)
        {
            if (strlen($news->contenu()) > $nombreCaracteres)
            {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);
                // Donné dans la solution, cette ligne permet de remplacer le dernier mot par 3 petits points
                // si la longeur du texte est supérieure au maximum de caractères
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $news->setContenu($debut);
            }
        }
        $this->page->addVar('listeNews', $listeNews);
    }

    // Solution
    public function executeShow(\OCFram\HTTPRequest $request){
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if (empty($news))
        {
            $this->app->httpResponse()->redirect404();
        }

        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
    }

    //Solution
    public function executeInsertComment(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Ajout d\'un commentaire');

        if ($request->postExists('pseudo'))
        {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('pseudo'),
                'contenu' => $request->postData('contenu')
            ]);

            if ($comment->isValid())
            {
                $this->managers->getManagerOf('Comments')->save($comment);

                $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

                $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
            }
            else
            {
                $this->page->addVar('erreurs', $comment->erreurs());
            }

            $this->page->addVar('comment', $comment);
        }
    }
}