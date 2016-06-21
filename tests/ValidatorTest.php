<?php

namespace Tests;

use Generics\Exceptions\InvalidTypeException;
use Generics\Dictionary;
use stdClass;

class ValidatorTest extends TestCase
{
    public function testStringValidator()
    {
        $generic = new Dictionary('string');
        $generic[] = 'bar';
        $this->assertEquals('bar', $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but stdClass was given.');
        $generic[] = new stdClass();
    }

    public function testIntegerValidator()
    {
        $generic = new Dictionary('int');
        $generic[] = 1;
        $this->assertEquals(1, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic[2] = 'foo';
    }

    public function testDoubleValidator()
    {
        $generic = new Dictionary('double');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testDoubleValidatorAlias()
    {
        $generic = new Dictionary('float');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidator()
    {
        $generic = new Dictionary('boolean');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidatorAlias()
    {
        $generic = new Dictionary('bool');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testObjectValidator()
    {
        $generic = new Dictionary(stdClass::class);
        $generic[] = new stdClass();
        $this->assertInstanceOf(stdClass::class, $generic[0]);

        $this->setExpectedException(
            InvalidTypeException::class,
            'Type must be stdClass, but Generics\Dictionary was given.'
        );
        $generic[] = new Dictionary('integer');
    }

    public function testObjectValidatorInvalid()
    {
        $this->setExpectedException(
            InvalidTypeException::class,
            'This\Class\Does\Not\Exist is an invalid type or does not exist as a class.'
        );
        new Dictionary('This\Class\Does\Not\Exist');
    }
}