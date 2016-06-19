<?php
namespace Generics;

use ArrayAccess;
use Countable;
use Generics\Exceptions\GenericsException;
use Generics\Exceptions\InvalidTypeException;
use Generics\Interfaces\IValidator;
use Generics\Validators\BooleanValidator;
use Generics\Validators\DoubleValidator;
use Generics\Validators\IntegerValidator;
use Generics\Validators\ObjectValidator;
use Generics\Validators\StringValidator;
use Iterator;
use Serializable;

class GenericDictionary implements ArrayAccess, Countable, Iterator, Serializable
{
    /**
     * @var IValidator
     */
    protected $keyValidator;

    /**
     * @var IValidator
     */
    protected $valueValidator;

    /**
     * @var array
     */
    protected $items = [];

    const SUPPORTED_TYPES = [
        'boolean' => BooleanValidator::class,
        'bool' => BooleanValidator::class,
        'integer' => IntegerValidator::class,
        'int' => IntegerValidator::class,
        'double' => DoubleValidator::class,
        'float' => DoubleValidator::class,
        'string' => StringValidator::class
    ];

    const DEFAULT_VALIDATOR = ObjectValidator::class;

    /**
     * Generic constructor. Allowed types for TValue/TKey:
     * * boolean/bool
     * * integer/int
     * * double/float
     * * string
     * * [any class, e.g.: stdClass/Generic/...]
     *
     * @param string $TValue Type for each item. This will be returned instead of TValue.
     * @param string $TKey Type for each key. This will be returned instead of TKey.
     * @param array|Iterator|GenericDictionary $data Data to fill. Must be compatible.
     */
    public function __construct(string $TValue, string $TKey = 'integer', $data = null)
    {
        $this->valueValidator = $this->getValidatorFor($TValue);
        $this->keyValidator = $this->getValidatorFor($TKey);

        if (!empty($data)) {
            $this->merge($data);
        }
    }

    /**
     * Get types for generic object. Can be used for compatibility check.
     *
     * @return array [TKey, TValue]
     */
    public function getTypes() : array
    {
        return [$this->keyValidator->getName(), $this->valueValidator->getName()];
    }

    /**
     * Validate if both Generics or arrays are compatible.
     *
     * @param GenericDictionary|Iterator|array $other
     * @return bool
     */
    public function isCompatible($other) : bool
    {
        try {
            if ($other instanceof GenericDictionary) {
                return $this->getTypes() === $other->getTypes();
            } elseif (is_array($other) || $other instanceof Iterator) {
                foreach ($other as $key => $value) {
                    $this->validateEntry($key, $value);
                }
                return true;
            }
        } catch (InvalidTypeException $e) {
        }
        return false;
    }

    /**
     * Set the validator for given type.
     *
     * @param string $type
     * @return IValidator
     */
    protected function getValidatorFor(string $type) : IValidator
    {
        $supportedTypes = self::SUPPORTED_TYPES;

        if (isset($supportedTypes[$type])) {
            return new $supportedTypes[$type];
        }
        $defaultValidator = self::DEFAULT_VALIDATOR;
        return new $defaultValidator($type);
    }

    /**
     * Get and then unset item.
     *
     * @param TKey $key
     * @return TValue
     */
    public function pull($key)
    {
        $value = $this->offsetGet($key);
        $this->offsetUnset($key);

        return $value;
    }

    /**
     * Push item. Only possible when using integer as TKey.
     *
     * @param TValue $value
     * @return $this
     */
    public function push($value) : GenericDictionary
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Put item with key.
     *
     * @param TKey $key
     * @param TValue $value
     * @return $this
     */
    public function put($key, $value) : GenericDictionary
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Merge two arrays into current Generic. This will fail if some keys or values are of a different type.
     *
     * @param array|Iterator|GenericDictionary $array
     * @return $this
     * @throws InvalidTypeException
     */
    public function merge($array) : GenericDictionary
    {
        if (!$this->isCompatible($array)) {
            throw new InvalidTypeException('You cannot merge this array, since it contains incompatible types.');
        }

        if ($array instanceof GenericDictionary) {
            $this->items = array_merge($this->items, $array->items);
        } else {
            foreach ($array as $key => $value) {
                $this->put($key, $value);
            }
        }

        return $this;
    }

    /**
     * Get array of items.
     *
     * @return array [TKey => TValue]
     */
    public function toArray() : array
    {
        return $this->items;
    }

    /**
     * Check if Generic has given value.
     *
     * @param TValue $value
     * @return bool
     */
    public function has($value) : bool
    {
        return in_array($value, $this->items);
    }

    /**
     * Get current value.
     *
     * @return TValue|false
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Get next value.
     *
     * @return TValue|false
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * Get previous element.
     *
     * @return TValue|false
     */
    public function previous()
    {
        return prev($this->items);
    }

    /**
     * Get current key.
     *
     * @return TKey|false
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * Check if current pointer is valid.
     *
     * @return bool
     */
    public function valid() : bool
    {
        return $this->key() !== null;
    }

    /**
     * Set pointer to start.
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * Check if key exists.
     *
     * @param TKey $key
     * @return bool
     */
    public function offsetExists($key) : bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get element for key.
     *
     * @param TKey $key
     * @return TValue
     * @throws GenericsException
     */
    public function offsetGet($key)
    {
        if ($this->offsetExists($key)) {
            return $this->items[$key];
        }

        throw new GenericsException('Invalid offset.');
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
        if (is_null($key) && !$this->keyValidator instanceof IntegerValidator) {
            throw new InvalidTypeException(
                'Cannot increment keys of type ' . $this->keyValidator->getName() . '. Please specify manually.'
            );
        }

        if (!$this->valueValidator->isValid($value)) {
            // Value invalid.
            throw InvalidTypeException::forValue($value, $this->valueValidator->getName());
        }

        if (!is_null($key) && !$this->keyValidator->isValid($key)) {
            // Key invalid.
            throw InvalidTypeException::forValue($key, $this->keyValidator->getName());
        }

        return true;
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
     * Remove item.
     *
     * @param TKey $key
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Count items.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->items);
    }

    /**
     * Serialize object.
     *
     * @return string
     */
    public function serialize() : string
    {
        return $this->__toString();
    }

    /**
     * Unserialize string.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->items = json_decode($serialized, true);
    }

    /**
     * Convert Generic to JSON string.
     *
     * @return string
     */
    public function __toString() : string
    {
        return json_encode($this->items);
    }
}