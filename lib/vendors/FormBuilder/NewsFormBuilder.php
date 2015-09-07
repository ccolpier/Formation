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
                new \Validator\MaxLengthValidator('L\'auteur spécifié est trop long (20 caractères maximum)', 20),
                new \Validator\NotNullValidator('Merci de spécifier l\'auteur de la news'),
            ],
        ]))
            ->add(new \Field\StringField([
                'label' => 'Titre',
                'name' => 'titre',
                'maxLength' => 100,
                'validators' => [
                    new \Validator\MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                    new \Validator\NotNullValidator('Merci de spécifier le titre de la news'),
                ],
            ]))
            ->add(new \Field\TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new \Validator\NotNullValidator('Merci de spécifier le contenu de la news'),
                ],
            ]));
    }
}