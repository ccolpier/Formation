<?php
namespace FormBuilder;

class ConnectFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo',
            'name' => 'nickname',
            'maxLength' => 20,
            'validators' => [
                new \Validator\NotNullValidator('Merci de sp�cifier le pseudo'),
            ],
        ]))
            ->add(new \Field\PasswordField([
                'label' => 'Mot de passe',
                'name' => 'password',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\NotNullValidator('Merci de sp�cifier le mot de passe'),
                ],
            ]));
    }
}