<?php
namespace FormBuilder;

class SearchMemberFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo à rechercher',
            'name' => 'nickname',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le pseudo spécifié est trop long (20 caractères maximum)', 20),
                new \Validator\NotNullValidator('Merci de spécifier le pseudo'),
            ],
        ]));
    }
}