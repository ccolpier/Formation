<?php

namespace App\FrontEnd\Modules\News;

use Model\NewsManager;
use \OCFram\HTTPRequest;
use \Entity\Comment;

class NewsController extends \OCFram\BackController {
    public function executeIndex(HTTPRequest $request){
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

    // Solution
    public function executeShow(HTTPRequest $request){
        /** @var $NewManager NewsManager */
        $NewManager = $this->managers->getManagerOf('News');
        $news = $NewManager->getUnique($request->getData('id'));

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
        // Si le formulaire a �t� envoy�.
        if ($request->method() == 'POST')
        {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu')
            ]);
        }
        else
        {
            $comment = new Comment;
            $comment->setNews($request->getData('news'));
        }

        $formBuilder = new \FormBuilder\CommentFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Le commentaire a bien �t� ajout�, merci !');
            $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
        }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un commentaire');
    }
}