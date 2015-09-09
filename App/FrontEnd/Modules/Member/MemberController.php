<?php

namespace App\FrontEnd\Modules\Member;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \Others\DateTimeFram;

class MemberController extends \OCFram\BackController{
    /** Affiche la page du membre actuel ou la page d'un membre donn� si celui-ci est pr�cis�*/
    // TODO : � finir, l'affichage des informations du membre connect� essentiellement
    public function executeIndex(HTTPRequest $request){
        /** @var $manager \Model\MembersManager*/
        $manager = $this->managers->getManagerOf('Members');
        $user = $this->app()->user();

        //R�cup�ration de l'�ventuel id dans l'URL
        $id = $request->getData('id');

        /** @var $member Member*/
        $member = NULL;

        //Si le membre essaie de visiter sa page de membre s mais n'est pas connect�, on le redirige
        if(empty($id) && !$user->isAuthenticated()){
            $user->setFlash('Vous devez �tre connect� pour voir votre page de membre.');
            $this->app()->HTTPResponse()->redirect('/formation/connect.html');
        }
        //Si l'id de la page est la m�me que celle de la session connect�e ou que l'id n'est pas fournie sur connexion => page perso
        //Si l'id n'est pas fourni mais que le membre est connect� => page perso
        elseif($user->isAuthenticated() && ($id == $user->getAttribute('connected_id') || empty($id))){
            $this->page->addVar('mode', 'private');
            $member = $manager->getUnique($user->getAttribute('connected_id'));
        }
        //Sinon affichage priv�
        else{
            $this->page->addVar('mode', 'public');
            $member = $manager->getUnique($id);
        }

        //Page erreur si le membre n'existe pas
        if(empty($member)){
            $user->setFlash('Le membre n\'a pas pu �tre trouv�.');
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
        //Si le membre est connect� on le redirige
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('Vous �tes d�j� inscrit et connect�.');
            $this->app->httpResponse()->redirect('/formation/index.html');
        }

        //Si le formulaire a �t� rempli
        if ($request->method() == 'POST'){
            //On cr�e un objet membre selon les donn�es
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

        // Appels � des fonctions du manager pour v�rifier que le pseudo n'est pas d�j� pris et que le formulaire est valide
        if(!empty($member->nickname()) && !$manager->nicknameAlreadyTaken($member->nickname()))
        {
            $formHandler = new \OCFram\FormHandler($form, $manager, $request);
            if ($formHandler->process())
            {
                //Si le membre a �t� correctement inscrit, on redirige vers la page d'index du membre en connectant le membre
                $user->setFlash('Vous avez �t� correctement inscrit !');
                $this->connectMember($member->nickname());
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
        }
        // Si juste le nom est pris, on notifie l'utilisateur
        if($manager->nicknameAlreadyTaken($member->nickname())){
            $this->app->user()->setFlash('Ce pseudo est d�j� pris !');
        }
        // Si on a pas valid�, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('form', $form->createView());
    }

    /** Affiche le formulaire de connexion ou finalise la connexion */
    public function executeConnect(HTTPRequest $request){
        //Si le membre est connect� on le redirige
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('Vous �tes d�j� inscrit et connect�.');
            $this->app->httpResponse()->redirect('/formation/index.html');
        }

        /** @var $login_name string*/
        $login_name = '';
        /** @var $login_password string*/
        $login_password = '';

        //Si le formulaire a �t� rempli
        if ($request->method() == 'POST'){
            //On r�cup�re les donn�es de connexion
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

        //On v�rifie que le combo mot de passe et nom correspond � la base
        /** @var $found_member Member*/
        $found_member = $manager->getUniqueByName($login_name);

        // Bon cas: on connecte
        if(!empty($found_member)) {
            if( $found_member->nickname() == $login_name && $found_member->password() == $login_password && $request->method() == 'POST' && $form->isValid()){
                $this->connectMember($login_name);
                $user->setFlash('Vous avez �t� connect�.');
                $this->app->httpResponse()->redirect('/formation/member.html');
            }
            //Sinon on reboucle avec alertes
            if($found_member->nickname() != $login_name || $found_member->password() != $login_password){
                $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez r�essayer.');
            }
        }
        elseif(!empty($login_name) || !empty($login_password)){
            $user->setFlash('Combinaison pseudo/mot de passe incorrecte. Veulliez r�essayer.');
        }
        // Si on a pas valid�, on recharge la page mais avec les anciennes valeurs du formulaire
        $this->page->addVar('form', $form->createView());
    }

    // D�connecte le membre si il �tait connect�
    public function executeLogout(HTTPRequest $request){
        $user = $this->app()->user();
        if($user->isAuthenticated()) {
            $user->setFlash('Vous avez �t� deconnect�.');
            $user->setAuthenticated(false);
            $user->setAttribute('connected_id', NULL);
        }
        else{
            $user->setFlash('Vous n\'�tes pas connect�.');
        }
        $this->app->httpResponse()->redirect('/formation/index.html');
    }

    //Page de restauration du mot de passe du membre
    public function executeRestore(HTTPRequest $request){
        //Ne marche pas si le membre est connect� (il peut avoir acc�s � son mdp) => redirection sur sa page
        $user = $this->app->user();
        if($user->isAuthenticated()){
            $user->setFlash('V�rifiez directement votre mot de passe depuis votre page personnelle.');
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

        //Sinon on v�rifie le formulaire
        if ($request->method() == 'POST'){
            //On r�cup�re les donn�es de mise � jour de code
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
            $user->setFlash('Fournissez un pseudo � r�cup�rer et �nventuellement le code de r�cup�ration associ� si il a �t� re�u.');
        }

        //Mauvais pseudo fourni => on notifie
        if(!empty($name)&& empty($member)){
            $user->setFlash('Le pseudo pass� en param�tre n\'�xiste pas.');
        }

        //Pseudo fourni correct
        if(!empty($member) && $member->isValid()){
            /** @var $existingMissing \Entity\MissingPass*/
            $existingMissing = $missingManager->get($member); //Missing pr� �xistant
            //Pas de missing existant => on en cr�e un
            if(empty($existingMissing)){
                $missingManager->add($member);
                $user->setFlash('Un code vous a �t� envoy� par mail. Remplissez ce formulaire avec le code');
                //TODO : envoi email
            }
            //Missing existant => on v�rifie si le code a �t� fourni
            else{
                //Si code non fourni => on notifie
                if(empty($code)){
                    $user->setFlash('Fournissez le code qui a �t� envoy� par mail');
                }
                //Si code est fourni on v�rifie si il est correct
                else{
                    //Si code correct, on affiche le mot de passe du membre et on delete le missing
                    if($code == $existingMissing->code()){
                        $missingManager->delete($member);
                        $user->setFlash('Votre mot de passe a �t� r�cup�r�. Le voil�: '.$member->password());
                    }
                    //Sinon on notifie
                    else{
                        $user->setFlash('Le code fourni n\'est pas celui envoy�. V�rifiez vos derniers mails.');
                    }
                }
            }
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeUpdate(HTTPRequest $request){
        $user = $this->app->user();
        //Redirection si non connect�
        if(!$user->isAuthenticated()){
            $user->setFlash('Vous devez �tre connect� pour modifier votre profil.');
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

        //Si il n'y a pas de donn�es envoy�es ou que le formulaire est invalide
        if($request->method() == 'POST' && $form->isValid()) {
            //Message d'erreur
            $message = '';

            //On v�rifie que le nouveau pseudo n'est pas pris
            $newName = $request->postData('nickname');
            if(!empty($newName) && $newName != $member->nickname() && $manager->nicknameAlreadyTaken($newName)){
                $message .= 'Le pseudo est d�j� pris.\n';
            }
            if(!empty($newName) && strlen($newName) < 5){
                $message .= 'Le pseudo est trop court.\n';
            }
            //On v�rifie que le nouveau mot de passe est confirm�
            $newPassword = $request->postData('password');
            $newPassword_confirm = $request->postData('password_confirm');
            if(!empty($newPassword) && $newPassword != $newPassword_confirm){
                $message .= 'Veuillez confirmer le mot de passe.\n';
            } if(!empty($newPassword) && strlen($newPassword) < 5){
                $message .= 'Le mot de passe est trop court.\n';
            }
            $newEmail = $request->postData('email');

            //Si tout est bon, on met � jour
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