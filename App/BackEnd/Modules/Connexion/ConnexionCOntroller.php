<?php
namespace App\Backend\Modules\Connexion;

class ConnexionController extends \OCFram\BackController
{
    //Solution
    public function executeIndex(\OCFram\HTTPRequest $request)
    {
        $this->page->addVar('title', 'Connexion');

        if ($request->postExists('login'))
        {
            $login = $request->postData('login');
            echo "$login <br>";
            $password = $request->postData('password');
            echo "$password <br><br>";

            echo $this->app->config()->get('login').'<br>';
            echo $this->app->config()->get('pass').'<br>';

            if ($login == $this->app->config()->get('login') && $password == $this->app->config()->get('pass'))
            {
                $this->app->user()->setAuthenticated(true);
                $this->app->httpResponse()->redirect('.');
            }
            else
            {
                $this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
            }
        }
    }
}