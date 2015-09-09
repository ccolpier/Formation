<?php
namespace OCFram;

abstract class FormBuilder
{
    /** @var Form */
    protected $form;

    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }

    abstract public function build();

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function form()
    {
        return $this->form;
    }
}