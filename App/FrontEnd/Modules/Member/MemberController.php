<?php

namespace App\FrontEnd\Modules\Member;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \Others\DateTimeFram;

class MemberController extends \OCFram\BackController{
    /** Affiche la page du membre actuel ou la page d'un membre donn� si celui-ci est pr�cis�*/
    // TODO : � finir, l'affichage des informations du membre connect� essentiellement
    public function executeIndex(HTTPRequest $request){
        $manager = $this->managers->getManagerOf('Members');

        //R�cup�ration de l'�ventuel id dans l'URL
        $id = $request->getData('id');
        //Si l'ID du membre est fourni
        if(is_int($id)){
            $member = $manager->getUnique($id);
            $this->page->addVar('member', $member);
        }
        //Sinon on affiche le membre actuel si l'utilisateur est connect�
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


        //Cas:
        //Mauvais pseudo fourni => on notifie
        if(!empty($name)&& empty($missingPass->member())){
            $user->setFlash('Le pseudo pass� en param�tre n\'�xiste pas.');
        }
        //Pseudo fourni correct
        if(!empty($missingPass->member())){
            /** @var $member Member*/
            $member = $missingPass->member(); //Membre
            /** @var $existingMissing \Entity\MissingPass*/
            $existingMissing = $missingManager->get($member); //Missing pr� �xistant
            //Pas de missing existant => on en cr�e un
            if(empty($existingMissing)){
                $missingManager->add($member);
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
                        $user->setFlash('Le code foutni n\'est pas celui envoy�. V�rifiez vos derniers mails.');
                    }
                }
            }
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeUpdate(HTTPRequest $request){

    }

    public function executeSearch(HTTPRequest $request){

    }
}