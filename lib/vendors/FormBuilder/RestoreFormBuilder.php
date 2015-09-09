<?php
namespace FormBuilder;

class RestoreFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo',
            'name' => 'name',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le pseudo spécifié est trop long (20 caractères maximum)', 20),
                new \Validator\NotNullValidator('Merci de spécifier le pseudo'),
            ],
        ]))
            ->add(new \Field\StringField([
                'label' => 'Code de récupération',
                'name' => 'code',
                'maxLength' => 8,
                'validators' => [
                    new \Validator\ExactLengthValidator('Le pseudo spécifié n\'est pas de bonne longueur (8 caractères exactement)', 8),
                    new \Validator\NotNullValidator('Merci de remplir le code envoyé par mail'),
                ],
            ]));
    }
}