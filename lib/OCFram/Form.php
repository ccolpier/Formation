<?php
namespace OCFram;

class Form
{
    protected $entity;
    protected $fields = [];

    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }

    public function add(Field $field)
    {
        $attr = $field->name(); // On récupère le nom du champ.
        if(is_callable([$this->entity, $attr])) {
            $field->setValue($this->entity->$attr()); // On assigne la valeur correspondante au champ.
        }

        $this->fields[] = $field; // On ajoute le champ passé en argument à la liste des champs.
        return $this;
    }

    public function fields(){
        return $this->fields;
    }

    public function createView()
    {
        $view = '';

        // On génère un par un les champs du formulaire.
        /** @var $field Field*/
        foreach ($this->fields as $field)
        {
            $view .= $field->buildWidget().'<br />';
        }

        $view .= '<p id="validationMessage"/></p>';
        return $view;
    }

    public function isValid()
    {
        $valid = true;

        // On vérifie que tous les champs sont valides.
        /** @var $field Field*/
        foreach ($this->fields as $field)
        {
            if (!$field->isValid())
            {
                $valid = false;
            }
        }

        return $valid;
    }

    public function entity()
    {
        return $this->entity;
    }

    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function initValues(){
//        foreach($this->entity as $attribut => $value){
//            /** @var $field Field*/
//            foreach($this->fields as $field){
//                if($field->name() == $attribut){
//                    echo 'a';
//                    $field->setValue($value);
//                }
//            }
//        }

        /** @var $field Field*/
        foreach($this->fields as $field){
            $name = $field->name();
            if(is_callable([$this->entity,$name])){
                try {
                    $val = call_user_func([$this->entity, $name]);
                    $field->setValue($val);
                } catch(\Exception $e) {}
            }
        }
    }

    public function ajaxFunctionName(){
        return 'formValidate()';
    }

    public function generateAjaxValidation(){
        $script = 'function '.$this->ajaxFunctionName().' {
        ';
        $script .= '$.ajax({
            method:"POST",
            url:"/formation/formvalidation.html",
            data:{';
        /** @var $field Field*/
        foreach ($this->fields as $field)
        {
            $script .= '"'.$field->name().'":document.getElementById("form" + "'.$field->name().'").value,';
        }
        $script .= '},
            success: function(response) {
                $("#validationMessage").empty();
                data = $.trim(response);
                if(data.length > 0){
                    node = document.createElement("P");
                    textnode = document.createTextNode(data);
                    node.appendChild(textnode);
                    document.getElementById("validationMessage").appendChild(node);
                }
                else {
                    $("#mainForm").removeAttr("onsubmit");
                    $("#mainForm").submit();
                }
            }
        })
        ';
        $script .= ' }';
        return $script;
    }
}