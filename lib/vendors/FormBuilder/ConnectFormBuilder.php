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
                new \Validator\MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\NotNullValidator('Merci de sp�cifier le pseudo'),
            ],
        ]))
            ->add(new \Field\PasswordField([
                'label' => 'Mot de passe',
                'name' => 'password',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le mot de passe sp�cifi� est trop long (20 caract�res maximum)', 20),
                    new \Validator\NotNullValidator('Merci de sp�cifier le mot de passe'),
                ],
            ]));
    }
}