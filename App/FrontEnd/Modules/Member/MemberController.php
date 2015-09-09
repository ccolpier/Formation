<?php

namespace App\FrontEnd\Modules\Member;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \Others\DateTimeFram;

class MemberController extends \OCFram\BackController{
    /** Affiche la page du membre actuel ou la page d'un membre donné si celui-ci est précisé*/
    // TODO : à finir, l'affichage des informations du membre connecté essentiellement
    public function executeIndex(HTTPRequest $request){
        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');
        $user = $this->app()->user();

        //Récupération de l'éventuel id dans l'URL
        $id = $request->getData('id');

        /** @var $member Member*/
        $member = NULL;

        //Si le membre essaie de visiter sa page de membre s mais n'est pas connecté, on le redirige
        if(empty($id) && !$user->isAuthenticated()){
            $user->setFlash('Vous devez être connecté pour voir votre page de membre.');
            $this->app()->HTTPResponse()->redirect('/formation/connect.html');
        }
        //Si l'id de la page est la même que celle de la session connectée ou que l'id n'est pas fournie sur connexion => page perso
        //Si l'id n'est pas fourni mais que le membre est connecté => page perso
        elseif($user->isAuthenticated() && ($id == $user->getAttribute('connected_id') || empty($id))){
            $this->page->addVar('mode', 'private');
            $member = $manager->getUnique($user->getAttribute('connected_id'));
        }
        //Sinon affichage privé
        else{
            $this->page->addVar('mode', 'public');
            $member = $manager->getUnique($id);
        }

        //Page erreur si le membre n'existe pas
        if(empty($member)){
            $user->setFlash('Le membre n\'a pas pu être trouvé.');
            $this->app->httpResponse()->redirect404();
        }
        //Page erreur si le membre est invalide
        if(!$member->isValid()){
            $user->setFlash('Le membre est invalide ou incomplet.');
            $this->app->httpResponse()->redirect404();
        }

        $this->page->addVar('member', $member);
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
        //$form->initValues();

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
        //$form->initValues();

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

        /** @var $memberManager \Model\MembersManager*/
        $memberManager = $this->managers->getManagerOf('Members');
        /** @var $missingManager \Model\MissingPassManager*/
        $missingManager = $this->managers->getManagerOf('MissingPass');
        /** @var $nickname string*/
        $name = NULL;
        /** @var $code string*/
        $code = NULL;

        //Sinon on vérifie le formulaire
        if ($request->method() == 'POST'){
            //On récupère les données de mise à jour de code
            $name = $request->postData('name');
            $code = $request->postData('code');
        }

        $missingPass = new \Entity\MissingPass([
            'member' => !empty($memberManager->getUniqueByName($name)) ? $memberManager->getUniqueByName($name) : new Member(),
            'code' => $code,
        ]);

        $formBuilder = new \FormBuilder\RestoreFormBuilder($missingPass);
        $formBuilder->build();

        /** @var $form \OCFram\Form*/
        $form = $formBuilder->form();


        /** @var $member Member*/
        $member = $missingPass->member(); //Membre

        //Pas de pseudo fourni
        if(empty($name) && empty($member)){
            $user->setFlash('Fournissez un pseudo à récupérer et énventuellement le code de récupération associé si il a été reçu.');
        }

        //Mauvais pseudo fourni => on notifie
        if(!empty($name)&& empty($member)){
            $user->setFlash('Le pseudo passé en paramètre n\'éxiste pas.');
        }

        //Pseudo fourni correct
        if(!empty($member) && $member->isValid()){
            /** @var $existingMissing \Entity\MissingPass*/
            $existingMissing = $missingManager->get($member); //Missing pré éxistant
            //Pas de missing existant => on en crée un
            if(empty($existingMissing)){
                $missingManager->add($member);
                $user->setFlash('Un code vous a été envoyé par mail. Remplissez ce formulaire avec le code');
                //TODO : envoi email
            }
            //Missing existant => on vérifie si le code a été fourni
            else{
                //Si code non fourni => on notifie
                if(empty($code)){
                    $user->setFlash('Fournissez le code qui a été envoyé par mail');
                }
                //Si code est fourni on vérifie si il est correct
                else{
                    //Si code correct, on affiche le mot de passe du membre et on delete le missing
                    if($code == $existingMissing->code()){
                        $missingManager->delete($member);
                        $user->setFlash('Votre mot de passe a été récupéré. Le voilà: '.$member->password());
                    }
                    //Sinon on notifie
                    else{
                        $user->setFlash('Le code fourni n\'est pas celui envoyé. Vérifiez vos derniers mails.');
                    }
                }
            }
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeUpdate(HTTPRequest $request){
        $user = $this->app->user();
        //Redirection si non connecté
        if(!$user->isAuthenticated()){
            $user->setFlash('Vous devez être connecté pour modifier votre profil.');
            $this->app()->HTTPResponse()->redirect('/formation/connect.html');
        }

        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');

        /** @var $member Member*/
        $member = $manager->getUnique($user->getAttribute('connected_id'));
        if(empty($member)){
            $user->setFlash('Erreur pour retrouver les informations.');
            $this->app()->HTTPResponse()->redirect404();
        }

        //Informations du formulaire
        $formBuilder = new \FormBuilder\UpdateProfileFormBuilder($member);
        $formBuilder->build();
        $form = $formBuilder->form();

        //Si il n'y a pas de données envoyées ou que le formulaire est invalide
        if($request->method() == 'POST' && $form->isValid()) {
            //Message d'erreur
            $message = '';

            //On vérifie que le nouveau pseudo n'est pas pris
            $newName = $request->postData('nickname');
            if(!empty($newName) && $newName != $member->nickname() && $manager->nicknameAlreadyTaken($newName)){
                $message .= 'Le pseudo est déjà pris.\n';
            }
            if(!empty($newName) && strlen($newName) < 5){
                $message .= 'Le pseudo est trop court.\n';
            }
            //On vérifie que le nouveau mot de passe est confirmé
            $newPassword = $request->postData('password');
            $newPassword_confirm = $request->postData('password_confirm');
            if(!empty($newPassword) && $newPassword != $newPassword_confirm){
                $message .= 'Veuillez confirmer le mot de passe.\n';
            } if(!empty($newPassword) && strlen($newPassword) < 5){
                $message .= 'Le mot de passe est trop court.\n';
            }
            $newEmail = $request->postData('email');

            //Si tout est bon, on met à jour
            if(empty($message)){
                $newMember = clone $member;
                $newMember->setNickname(empty($newName) ? $member->nickname() : $newName);
                $newMember->setPassword(empty($newPassword) ? $member->password() : $newPassword);
                $newMember->setEmail(empty($newEmail) ? $member->email() : $newEmail);

                $manager->modify($newMember);

                $this->app()->HTTPResponse()->redirect('/formation/member.html');
            }
            //Sinon on notifie
            else{
                $user->setFlash($message);
            }
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeSearch(HTTPRequest $request){

    }
}