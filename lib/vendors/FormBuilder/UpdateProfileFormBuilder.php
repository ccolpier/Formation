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
                new \Validator\MaxLengthValidator('Le pseudo spécifié est trop long (20 caractères maximum)', 20),
            ],
        ]))
            ->add(new \Field\PasswordField([
                'label' => 'Nouveau mot de passe',
                'name' => 'password',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le mot de passe spécifié est trop long (20 caractères maximum)', 20),
                ],
            ]))
            ->add(new \Field\PasswordField([
                'label' => 'Confirmation mot de passe',
                'name' => 'password_confirm',
                'maxLength' => 20,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le mot de passe spécifié est trop long (20 caractères maximum)', 20),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'E-mail',
                'name' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('L\'adresse email spécifiée est trop longue (50 caractères maximum)', 50),
                    new \Validator\IsEmailValidator('Veuillez renseigner une adresse email valide.'),
                ],
            ]));
    }
}