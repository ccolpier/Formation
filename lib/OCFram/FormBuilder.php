<?php
namespace OCFram;

abstract class FormBuilder
{
    /** @var Form */
    protected $form;

    public function __construct(Entity $entity, $validation = true, $validationUrl = '/validation.html', $submitText = 'Submit')
    {
        $this->setForm(new Form($entity, $validation, $validationUrl, $submitText));
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