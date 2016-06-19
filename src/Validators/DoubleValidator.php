<?php

namespace Generics\Validators;

use Generics\Interfaces\IValidator;

class DoubleValidator implements IValidator
{
    public function isValid($value) : bool
    {
        return is_double($value);
    }

    public function getName() : string
    {
        return 'double';
    }
}