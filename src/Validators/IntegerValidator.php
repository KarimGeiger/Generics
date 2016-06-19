<?php

namespace Generics\Validators;

use Generics\Interfaces\IValidator;

class IntegerValidator implements IValidator
{
    public function isValid($value) : bool
    {
        return is_int($value);
    }

    public function getName() : string
    {
        return 'integer';
    }
}