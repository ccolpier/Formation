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
                new \Validator\MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\NotNullValidator('Merci de sp�cifier le pseudo'),
            ],
        ]))
            ->add(new \Field\StringField([
                'label' => 'Code de r�cup�ration',
                'name' => 'code',
                'maxLength' => 8,
                'validators' => [
                    new \Validator\ExactLengthValidator('Le pseudo sp�cifi� n\'est pas de bonne longueur (8 caract�res exactement)', 8),
                    new \Validator\NotNullValidator('Merci de remplir le code envoy� par mail'),
                ],
            ]));
    }
}