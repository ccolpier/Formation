<?php
namespace Validator;

class IsEmailValidator extends \OCFram\Validator
{
    public function isValid($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}