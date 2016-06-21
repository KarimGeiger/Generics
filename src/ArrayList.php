<?php
namespace Generics;

use Generics\Exceptions\InvalidTypeException;
use Iterator;

class ArrayList extends Dictionary
{
    /**
     * ArrayList constructor. Key will always be int. Allowed types for TValue:
     * * boolean/bool
     * * integer/int
     * * double/float
     * * string
     * * [any class, e.g.: stdClass/Generic/...]
     *
     * @param string $TValue Type for each item. This will be returned instead of TValue.
     * @param array|Iterator|Dictionary $data Data to fill. Must be compatible.
     */
    public function __construct(string $TValue, $data = null)
    {
        parent::__construct('integer', $TValue, $data);
    }

    /**
     * Push item.
     *
     * @param TValue $value
     * @return $this
     */
    public function push($value) : ArrayList
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Add item.
     *
     * @param TKey $key
     * @param TValue $value
     * @throws InvalidTypeException
     */
    public function offsetSet($key, $value)
    {
        $this->validateEntry($key, $value);

        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Validate new entry. Return true on success, throw Exception on failure.
     *
     * @param mixed $key
     * @param mixed $value
     * @return bool
     * @throws InvalidTypeException
     */
    protected function validateEntry($key, $value) : bool
    {
        if (!$this->valueValidator->isValid($value)) {
            // Value invalid.
            throw InvalidTypeException::forValue($value, $this->valueValidator->getName());
        }

        if ($key !== null && !$this->keyValidator->isValid($key)) {
            // Key invalid.
            throw InvalidTypeException::forValue($key, $this->keyValidator->getName());
        }

        return true;
    }
}