<?php

namespace Tests;

use Generics\ArrayList;
use Generics\Exceptions\GenericsException;
use Generics\Exceptions\InvalidTypeException;
use Generics\Dictionary;

class ExceptionTest extends TestCase
{
    public function testMergeSelf()
    {
        $generic = new Dictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge(new Dictionary('integer', 'string'));
    }

    public function testMergeArray()
    {
        $generic = new Dictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge([1 => 'string', 'string' => 1]);
    }

    public function testMergeNotApplying()
    {
        $generic = new Dictionary('string', 'integer');
        try {
            $generic->merge([1 => 'string', 'string' => 1]);
        } catch (InvalidTypeException $e) {
            $this->assertCount(0, $generic);
        }
    }

    public function testMergeArrayTwo()
    {
        $generic = new Dictionary('string', 'integer');
        $this->setExpectedException(
            InvalidTypeException::class,
            'You cannot merge this array, since it contains incompatible types.'
        );
        $generic->merge('this cannot work.');
    }

    public function testInvalidOffset()
    {
        $generic = new Dictionary('integer', 'string', ['foo', 'bar']);
        $this->assertEquals('bar', $generic[1]);
        $this->setExpectedException(GenericsException::class, 'Invalid offset.');
        $generic[2];
    }

    public function testSetInvalidKey()
    {
        $generic = new Dictionary('integer', 'string');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic->put('string', 'foo');
    }

    public function testSetInvalidKeySecond()
    {
        $generic = new Dictionary('integer', 'string');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic['string'] = 'foo';
    }

    public function testSetInvalidKeyArrayList()
    {
        $generic = new ArrayList('integer');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic['string'] = 1;
    }

    public function testSetInvalidIncrement()
    {
        $generic = new Dictionary('string', 'string');
        $this->setExpectedException(
            InvalidTypeException::class,
            'Cannot increment keys of type string. Please specify manually or use ArrayList.'
        );
        $generic[] = 'foo';
    }

    public function testSetInvalidValue()
    {
        $generic = new Dictionary('integer', 'string');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but integer was given.');
        $generic->put(1, 1);
    }

    public function testSetInvalidValueSecond()
    {
        $generic = new Dictionary('integer', 'string');
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but integer was given.');
        $generic[1] = 1;
    }
}