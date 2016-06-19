<?php

namespace Tests;

use Generics\Exceptions\GenericsException;
use Generics\Exceptions\InvalidTypeException;
use Generics\GenericDictionary;

class ExceptionTest extends TestCase
{
    public function testMergeSelf()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge(new GenericDictionary('integer', 'string'));
    }

    public function testMergeArray()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge([1 => 'string', 'string' => 1]);
    }

    public function testMergeArrayTwo()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge('this cannot work.');
    }

    public function testInvalidOffset()
    {
        $generic = new GenericDictionary('string', 'integer', ['foo', 'bar']);
        $this->assertEquals('bar', $generic[1]);
        $this->setExpectedException(GenericsException::class, 'Invalid offset.');
        $generic[2];
    }

    public function testSetInvalidKey()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic->put('string', 'foo');
    }

    public function testSetInvalidKeySecond()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic['string'] = 'foo';
    }

    public function testSetInvalidIncrement()
    {
        $generic = new GenericDictionary('string', 'string');
        $this->setExpectedException(
            InvalidTypeException::class,
            'Cannot increment keys of type string. Please specify manually.'
        );
        $generic[] = 'foo';
    }

    public function testSetInvalidValue()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but integer was given.');
        $generic->put(1, 1);
    }

    public function testSetInvalidValueSecond()
    {
        $generic = new GenericDictionary('string', 'integer');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but integer was given.');
        $generic[] = 1;
    }
}