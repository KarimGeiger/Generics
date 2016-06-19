<?php

namespace Generics\Exceptions;

class InvalidTypeException extends GenericsException
{
    public static function forValue($givenValue, $expectedType)
    {
        if (gettype($givenValue) === 'object') {
            $givenType = get_class($givenValue);
        } else {
            $givenType = gettype($givenValue);
        }

        return new static(sprintf('Type must be %s, but %s was given.', $expectedType, $givenType));
    }
}