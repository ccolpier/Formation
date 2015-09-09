<?php
namespace FormBuilder;

class RegisterFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo',
            'name' => 'nickname',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le pseudo spécifié est trop long (20 caractères maximum)', 20),
                new \Validator\MinLengthValidator('Le pseudo spécifié est trop court (5 caractères minimum', 5),
                new \Validator\NotNullValidator('Merci de spécifier le pseudo'),
            ],
        ]))
            ->add(new \Field\PasswordField([
            'label' => 'Mot de passe',
            'name' => 'password',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le mot de passe spécifié est trop long (20 caractères maximum)', 20),
                new \Validator\MinLengthValidator('Le mot de passe spécifié est trop court (5 caractères minimum', 5),
                new \Validator\NotNullValidator('Merci de spécifier le mot de passe'),
            ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'E-mail',
                'name' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('L\'adresse email spécifiée est trop longue (50 caractères maximum)', 50),
                    new \Validator\NotNullValidator('Merci de spécifier votre adresse email'),
                    new \Validator\IsEmailValidator('Veuillez renseigner une adresse email valide.'),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'Prénom',
                'name' => 'firstname',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le prénom spécifié est trop long (50 caractères maximum)', 50),
                    new \Validator\NotNullValidator('Merci de spécifier votre prénom'),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'Nom',
                'name' => 'lastname',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le nom spécifié est trop long (50 caractères maximum)', 50),
                    new \Validator\NotNullValidator('Merci de spécifier votre nom'),
                ],
            ]))
            ->add(new \Field\DateField([
                'label' => 'Date de naissance',
                'name' => 'dateofbirth',
                'validators' => [
                    new \Validator\MaxLengthValidator('La date spécifiée est trop longue (format AAAA-MM-DD)', \Field\DateField::MAXCHARLEN),
                    new \Validator\NotNullValidator('Merci de spécifier votre date de naissance'),
                    new \Validator\IsDateValidator('Merci de spécifier un texte en forme de date (format AAAA-MM-DD)'),
                ],
            ]));
    }
}