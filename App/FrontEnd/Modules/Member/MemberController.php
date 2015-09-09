<?php

namespace App\FrontEnd\Modules\Member;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \Others\DateTimeFram;

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
        //Si le membre est connecté on le redirige
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $this->app->httpResponse()->redirect('/formation/index.html');
        }

        //Si le formulaire a été rempli
        if ($request->method() == 'POST'){
            //On crée un objet membre selon les données
            $member = new Member([
                'nickname' => $request->postData('nickname'),
                'password' => $request->postData('password'),
                'firstname' => $request->postData('firstname'),
                'lastname' => $request->postData('lastname'),
                'dateofbirth' => new DateTimeFram($request->postData('dateofbirth'), new \DateTimeZone("UTC")),
                'dateofregister' => new DateTimeFram(NULL, new \DateTimeZone("UTC")),
            ]);
        }
        else {
            $member = new Member();
        }

        $formBuilder = new \FormBuilder\RegisterFormBuilder($member);
        $formBuilder->build();

        /** @var $form \OCFram\Form*/
        $form = $formBuilder->form();
        $form->initValues();

        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');

        // Appels à des fonctions du manager pour vérifier que le pseudo n'est pas déjà pris et que le formulaire est valide
        if(!empty($member->nickname()) && !$manager->nicknameAlreadyTaken($member->nickname()))
        {
            $formHandler = new \OCFram\FormHandler($form, $manager, $request);
            if ($formHandler->process())
            {
                //Si le membre a été correctement inscrit, on redirige vers la page d'index du membre en connectant le membre
                $user->setFlash('Vous avez été correctement inscrit !');
                $user->setAttribute('connected_id', $manager->getIdByName($member->nickname()));
                $user->setAuthenticated(true);
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
        }
        // Si juste le nom est pris, on notifie l'utilisateur
        if($manager->nicknameAlreadyTaken($member->nickname())){
            $this->app->user()->setFlash('Ce pseudo est déjà pris !');
        }
        // Si on a pas validé, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('member', $member);
        $this->page->addVar('form', $form->createView());
    }

    public function executeConnect(HTTPRequest $request){
        //Si données reçues par session, alors on se connecte
    }

    public function executeLogout(HTTPRequest $request){
        $user = $this->app()->user();
        if($user->isAuthenticated()) {
            $user->setFlash('Vous avez été deconnecté.');
            $user->setAuthenticated(false);
            $user->setAttribute('connected_id', NULL);
        }
        else{
            $user->setFlash('Vous n\'êtes pas connecté.');
        }
        $this->app->httpResponse()->redirect('/formation/index.html');
    }

    public function executeRestore(HTTPRequest $request){

    }

    public function executeUpdate(HTTPRequest $request){

    }

    public function executeSearch(HTTPRequest $request){

    }
}