<?php

namespace App\FrontEnd\Modules\Member;
use \OCFram\HTTPRequest;
use \Entity\Member;

class MemberController extends \OCFram\BackController{
    /** Affiche la page du membre actuel ou la page d'un membre donné si celui-ci est précisé*/
    // TODO : à finir, l'affichage des informations du membre connecté essentiellement
    public function executeIndex(HTTPRequest $request){
        $manager = $this->managers->getManagerOf('Members');

        //Récupération de l'éventuel id dans l'URL
        $id = $request->getData('id');
        //Si l'ID du membre est fourni
        if(is_int($id)){
            $member = $manager->getUnique($id);
            $this->page->addVar('member', $member);
        }
        //Sinon on affiche le membre actuel si l'utilisateur est connecté
        else{
            $user = $this->app()->user();
            if($user->isAuthenticated()){
                $id = $user->getAttribute('name');
            }
        }

        //Page erreur si le membre n'existe pas
        if(empty($member)){
            $this->app->httpResponse()->redirect404();
        }
    }

    /** Affiche le formulaire d'inscription */
    public function executeRegister(HTTPRequest $request){
        //Si le formulaire a été rempli
        if ($request->method() == 'POST'){
            //On crée un objet membre selon les données
            $member = new Member([
                'nickname' => $request->getData('nickname'),
                'password' => $request->postData('password'),
                'firstname' => $request->postData('firstname'),
                'lastname' => $request->postData('lastname'),
                'dateofbirth' => $request->postData('dateofbirth')
            ]);
        }
        else {
            $member = new Member();
        }

        $formBuilder = new \FormBuilder\RegisterFormBuilder($member);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Members'), $request);

        //TODO : SI formulaire valide, process dans le modèle pour voir SI valeur acceptable ALORS insérer et rediriger vers page d'index SINON notifier valeur incorrecte SINON notifier formulaire incorrecte
        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
            $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
        }

        $this->page->addVar('member', $member);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Inscription');
    }

    public function executeConnect(HTTPRequest $request){

    }

    public function executeRestore(HTTPRequest $request){

    }

    public function executeUpdate(HTTPRequest $request){

    }

    public function executeSearch(HTTPRequest $request){

    }
}