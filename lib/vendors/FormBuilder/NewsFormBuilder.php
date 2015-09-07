<?php
namespace FormBuilder;

class NewsFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Auteur',
            'name' => 'auteur',
            'maxLength' => 20,
            'validators' => [
                new \Validator\MaxLengthValidator('L\'auteur sp�cifi� est trop long (20 caract�res maximum)', 20),
                new \Validator\NotNullValidator('Merci de sp�cifier l\'auteur de la news'),
            ],
        ]))
            ->add(new \Field\StringField([
                'label' => 'Titre',
                'name' => 'titre',
                'maxLength' => 100,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le titre sp�cifi� est trop long (100 caract�res maximum)', 100),
                    new \Validator\NotNullValidator('Merci de sp�cifier le titre de la news'),
                ],
            ]))
            ->add(new \Field\TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new \Validator\NotNullValidator('Merci de sp�cifier le contenu de la news'),
                ],
            ]));
    }
}