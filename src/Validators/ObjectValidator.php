<?php

namespace Generics\Validators;

use Generics\Exceptions\InvalidTypeException;
use Generics\Interfaces\IValidator;

class ObjectValidator implements IValidator
{
    protected $className;

    public function __construct(string $className)
    {
        if (!class_exists($className)) {
            throw new InvalidTypeException($className . ' is an invalid type or does not exist as a class.');
        }

        $this->className = $className;
    }

    public function isValid($value) : bool
    {
        return $value instanceof $this->className;
    }

    public function getName() : string
    {
        return $this->className;
    }
}