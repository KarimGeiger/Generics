<?php

namespace Generics\Validators;

use Generics\Interfaces\IValidator;

class StringValidator implements IValidator
{
    public function isValid($value) : bool
    {
        return is_string($value);
    }

    public function getName() : string
    {
        return 'string';
    }
}