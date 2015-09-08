<?php

namespace OCFram;

class Entity implements  \ArrayAccess{
    protected $id;
    protected $erreurs = array();

    use Hydrator;

    public function __construct(array $valeurs = array()){
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