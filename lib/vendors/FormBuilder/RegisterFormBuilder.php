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
                new \Validator\MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\MinLengthValidator('Le pseudo sp�cifi� est trop court (5 caract�res minimum', 5),
                new \Validator\NotNullValidator('Merci de sp�cifier le pseudo'),
            ],
        ]))
            ->add(new \Field\PasswordField([
            'label' => 'Mot de passe',
            'name' => 'password',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le mot de passe sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\MinLengthValidator('Le mot de passe sp�cifi� est trop court (5 caract�res minimum', 5),
                new \Validator\NotNullValidator('Merci de sp�cifier le mot de passe'),
            ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'E-mail',
                'name' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('L\'adresse email sp�cifi�e est trop longue (50 caract�res maximum)', 50),
                    new \Validator\NotNullValidator('Merci de sp�cifier votre adresse email'),
                    new \Validator\IsEmailValidator('Veuillez renseigner une adresse email valide.'),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'Pr�nom',
                'name' => 'firstname',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le pr�nom sp�cifi� est trop long (50 caract�res maximum)', 50),
                    new \Validator\NotNullValidator('Merci de sp�cifier votre pr�nom'),
                ],
            ]))
            ->add(new \Field\StringField([
                'label' => 'Nom',
                'name' => 'lastname',
                'maxLength' => 50,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le nom sp�cifi� est trop long (50 caract�res maximum)', 50),
                    new \Validator\NotNullValidator('Merci de sp�cifier votre nom'),
                ],
            ]))
            ->add(new \Field\DateField([
                'label' => 'Date de naissance',
                'name' => 'dateofbirth',
                'validators' => [
                    new \Validator\MaxLengthValidator('La date sp�cifi�e est trop longue (format AAAA-MM-DD)', \Field\DateField::MAXCHARLEN),
                    new \Validator\NotNullValidator('Merci de sp�cifier votre date de naissance'),
                    new \Validator\IsDateValidator('Merci de sp�cifier un texte en forme de date (format AAAA-MM-DD)'),
                ],
            ]));
    }
}