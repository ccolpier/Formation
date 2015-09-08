<?php
namespace Validator;

class MinLengthValidator extends \OCFram\Validator
{
    protected $minLength;

    public function __construct($errorMessage, $minLength)
    {
        parent::__construct($errorMessage);

        $this->setMinLength($minLength);
    }

    public function isValid($value)
    {
        return strlen($value) >= $this->minLength;
    }

    public function setMinLength($minLength)
    {
        $minLength = (int) $minLength;

        if ($minLength > 0)
        {
            $this->minLength = $minLength;
        }
        else
        {
            throw new \RuntimeException('La longueur minimale doit �tre un nombre sup�rieur � 0');
        }
    }
}