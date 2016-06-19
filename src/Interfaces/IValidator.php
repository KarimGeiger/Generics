<?php

namespace Generics\Interfaces;

interface IValidator
{
    /**
     * Validates value.
     * 
     * @param mixed $value
     * @return bool
     */
    public function isValid($value) : bool;

    /**
     * Get name of validator.
     * 
     * @return string
     */
    public function getName() : string;
}