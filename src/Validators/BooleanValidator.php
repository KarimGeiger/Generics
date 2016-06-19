<?php

namespace Generics\Validators;

use Generics\Interfaces\IValidator;

class BooleanValidator implements IValidator
{
    public function isValid($value) : bool
    {
        return is_bool($value);
    }

    public function getName() : string
    {
        return 'boolean';
    }
}