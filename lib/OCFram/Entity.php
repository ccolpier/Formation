<?php

namespace OCFram;

class Entity implements  \ArrayAccess{
    protected $id;

    public function __construct($valeurs){
        $this->hydrate($valeurs);
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        if(is_integer($id)){
            $this->id = $id;
        }
    }

    public function isNew(){
        return is_null($this->id);
    }

    public function hydrate(array $valeurs){
        foreach ($valeurs as $champ => $valeur)
        {
            $to_execute = 'set'.ucfirst($champ);

            if (is_callable([$this, $to_execute]))
            {
                $this->$to_execute($valeur);
            }
        }
    }

    public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var]))
        {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value)
    {
        $method = 'set'.ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method]))
        {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }
}