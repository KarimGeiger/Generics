<?php

namespace Generics\Interfaces;

interface IValidator
{
    /**
     * Validates
     * @param $value
     * @return bool
     */
    public function isValid($value) : bool;

    public function getName() : string;
}