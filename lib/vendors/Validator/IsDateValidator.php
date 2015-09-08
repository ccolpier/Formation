<?php
namespace Validator;

class IsDateValidator extends \OCFram\Validator
{
    public function isValid($value)
    {
        return (\DateTime::createFromFormat('Y-m-d', $value) !== FALSE);
    }
}