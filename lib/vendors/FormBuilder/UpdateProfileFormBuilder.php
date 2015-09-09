<?php
namespace FormBuilder;

class UpdateProfileFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo',
            'name' => 'nickname',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
            ],
        ]))
            ->add(new \Field\PasswordField([
                'label' => 'Nouveau mot de passe',
                'name' => 'password',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le mot de passe sp�cifi� est trop long (20 caract�res maximum)', 20),
                ],
            ]))
            ->add(new \Field\PasswordField([
                'label' => 'Confirmation mot de passe',
                'name' => 'password_confirm',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le mot de passe sp�cifi� est trop long (20 caract�res maximum)', 20),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'E-mail',
                'name' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('L\'adresse email sp�cifi�e est trop longue (50 caract�res maximum)', 50),
                    new \Validator\IsEmailValidator('Veuillez renseigner une adresse email valide.'),
                ],
            ]));
    }
}