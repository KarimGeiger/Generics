<?php

namespace Tests;

use Generics\Exceptions\InvalidTypeException;
use Generics\GenericDictionary;
use stdClass;

class ValidatorTest extends TestCase
{
    public function testStringValidator()
    {
        $generic = new GenericDictionary('string');
        $generic[] = 'bar';
        $this->assertEquals('bar', $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but stdClass was given.');
        $generic[] = new stdClass();
    }

    public function testIntegerValidator()
    {
        $generic = new GenericDictionary('int');
        $generic[] = 1;
        $this->assertEquals(1, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic[2] = 'foo';
    }

    public function testDoubleValidator()
    {
        $generic = new GenericDictionary('double');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testDoubleValidatorAlias()
    {
        $generic = new GenericDictionary('float');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidator()
    {
        $generic = new GenericDictionary('boolean');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidatorAlias()
    {
        $generic = new GenericDictionary('bool');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testObjectValidator()
    {
        $generic = new GenericDictionary(stdClass::class);
        $generic[] = new stdClass();
        $this->assertInstanceOf(stdClass::class, $generic[0]);

        $this->setExpectedException(
            InvalidTypeException::class,
            'Type must be stdClass, but Generics\GenericDictionary was given.'
        );
        $generic[] = new GenericDictionary('integer');
    }

    public function testObjectValidatorInvalid()
    {
        $this->setExpectedException(
            InvalidTypeException::class,
            'This\Class\Does\Not\Exist is an invalid type or does not exist as a class.'
        );
        new GenericDictionary('This\Class\Does\Not\Exist');
    }
}