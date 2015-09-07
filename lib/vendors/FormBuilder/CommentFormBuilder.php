<?php
namespace FormBuilder;


class CommentFormBuilder extends \OCFram\FormBuilder
{
    public function build()
    {
        $this->form->add(new \Field\StringField([
            'label' => 'Auteur',
            'name' => 'auteur',
            'maxLength' => 50,
            'validators' => [
                new \Validator\MaxLengthValidator('L\'auteur sp�cifi� est trop long (50 caract�res maximum)', 50),
                new \Validator\NotNullValidator('Merci de sp�cifier l\'auteur du commentaire'),
            ],
        ]))
            ->add(new \Field\TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 7,
                'cols' => 50,
                'validators' => [
                    new \Validator\NotNullValidator('Merci de sp�cifier votre commentaire'),
                ],
            ]));
    }
}