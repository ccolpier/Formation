<?php
namespace Validator;

class NotNullValidator extends \OCFram\Validator
{
    public function isValid($value)
    {
        return $value != '';
    }
}