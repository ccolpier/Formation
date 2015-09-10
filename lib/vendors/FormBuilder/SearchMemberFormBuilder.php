<?php
namespace FormBuilder;

class SearchMemberFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Pseudo � rechercher',
            'name' => 'nickname',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\NotNullValidator('Merci de sp�cifier le pseudo'),
            ],
        ]));
    }
}