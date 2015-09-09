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

    private function connectMember($nickname){
        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');
        $user = $this->app->user();
        $user->setAttribute('connected_id', $manager->getIdByName($nickname));
        $user->setAuthenticated(true);
    }

    /** Affiche le formulaire d'inscription ou finalise l'inscription*/
    public function executeRegister(HTTPRequest $request){
        //Si le membre est connecté on le redirige
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('Vous êtes déjà inscrit et connecté.');
            $this->app->httpResponse()->redirect('/formation/index.html');
        }

        //Si le formulaire a été rempli
        if ($request->method() == 'POST'){
            //On crée un objet membre selon les données
            $member = new Member([
                'nickname' => $request->postData('nickname'),
                'password' => $request->postData('password'),
                'email' => $request->postData('email'),
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
                $this->connectMember($member->nickname());
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
        }
        // Si juste le nom est pris, on notifie l'utilisateur
        if($manager->nicknameAlreadyTaken($member->nickname())){
            $this->app->user()->setFlash('Ce pseudo est déjà pris !');
        }
        // Si on a pas validé, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('form', $form->createView());
    }

    /** Affiche le formulaire de connexion ou finalise la connexion */
    public function executeConnect(HTTPRequest $request){
        //Si le membre est connecté on le redirige
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('Vous êtes déjà inscrit et connecté.');
            $this->app->httpResponse()->redirect('/formation/index.html');
        }

        /** @var $login_name string*/
        $login_name = '';
        /** @var $login_password string*/
        $login_password = '';

        //Si le formulaire a été rempli
        if ($request->method() == 'POST'){
            //On récupère les données de connexion
            $login_name = $request->postData('nickname');
            $login_password = $request->postData('password');
        }

        $member = new Member([
            'nickname' => $login_name,
            'password' => $login_password,
        ]);

        $formBuilder = new \FormBuilder\ConnectFormBuilder($member);
        $formBuilder->build();

        /** @var $form \OCFram\Form*/
        $form = $formBuilder->form();
        $form->initValues();

        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');

        //On vérifie que le combo mot de passe et nom correspond à la base
        /** @var $found_member Member*/
        $found_member = $manager->getUniqueByName($login_name);

        // Bon cas: on connecte
        if(!empty($found_member)) {
            if( $found_member->nickname() == $login_name && $found_member->password() == $login_password && $request->method() == 'POST' && $form->isValid()){
                $this->connectMember($login_name);
                $user->setFlash('Vous avez été connecté.');
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
            //Sinon on reboucle avec alertes
            if($found_member->nickname() != $login_name || $found_member->password() != $login_password){
                $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez réessayer.');
            }
        }
        elseif(!empty($login_name) || !empty($login_password)){
            $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez réessayer.');
        }
        // Si on a pas validé, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('form', $form->createView());
    }

    // Déconnecte le membre si il était connecté
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

    //Page de restauration du mot de passe du membre
    public function executeRestore(HTTPRequest $request){
        //Ne marche pas si le membre est connecté (il peut avoir accès à son mdp) => redirection sur sa page
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('Vérifiez directement votre mot de passe depuis votre page personnelle.');
            $this->app->httpResponse()->redirect('/formation/member.html');
        }

        /** @var $nickname string*/
        $nickname = NULL;
        /** @var $code string*/
        $code = NULL;

        //Sinon on vérifie le formulaire
        if ($request->method() == 'POST'){
            //On récupère les données de mise à jour de code
            $name = $request->postData('name');
            $code = $request->postData('code');
        }

        $formBuilder = new \FormBuilder\ConnectFormBuilder($member);
        $formBuilder->build();

        /** @var $form \OCFram\Form*/
        $form = $formBuilder->form();
        $form->initValues();

        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');

        //On vérifie que le combo mot de passe et nom correspond à la base
        /** @var $found_member Member*/
        $found_member = $manager->getUniqueByName($login_name);

        // Bon cas: on connecte
        if(!empty($found_member)) {
            if( $found_member->nickname() == $login_name && $found_member->password() == $login_password && $request->method() == 'POST' && $form->isValid()){
                $this->connectMember($login_name);
                $user->setFlash('Vous avez été connecté.');
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
            //Sinon on reboucle avec alertes
            if($found_member->nickname() != $login_name || $found_member->password() != $login_password){
                $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez réessayer.');
            }
        }
        elseif(!empty($login_name) || !empty($login_password)){
            $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez réessayer.');
        }
        // Si on a pas validé, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('form', $form->createView());
    }

    public function executeUpdate(HTTPRequest $request){

    }

    public function executeSearch(HTTPRequest $request){

    }
}