<?php

namespace Tests;

use Generics\ArrayList;
use Generics\Exceptions\InvalidTypeException;
use stdClass;

class ValidatorTest extends TestCase
{
    public function testStringValidator()
    {
        $generic = new ArrayList('string');
        $generic[] = 'bar';
        $this->assertEquals('bar', $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but stdClass was given.');
        $generic[] = new stdClass();
    }

    public function testIntegerValidator()
    {
        $generic = new ArrayList('int');
        $generic[] = 1;
        $this->assertEquals(1, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be integer, but string was given.');
        $generic[2] = 'foo';
    }

    public function testDoubleValidator()
    {
        $generic = new ArrayList('double');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testDoubleValidatorAlias()
    {
        $generic = new ArrayList('float');
        $generic[] = 1.0;
        $this->assertEquals(1.0, $generic[0]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be double, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidator()
    {
        $generic = new ArrayList('boolean');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testBooleanValidatorAlias()
    {
        $generic = new ArrayList('bool');
        $generic[] = true;
        $generic[] = false;
        $this->assertTrue($generic[0]);
        $this->assertFalse($generic[1]);

        $this->setExpectedException(InvalidTypeException::class, 'Type must be boolean, but integer was given.');
        $generic[] = 1;
    }

    public function testObjectValidator()
    {
        $generic = new ArrayList(stdClass::class);
        $generic[] = new stdClass();
        $this->assertInstanceOf(stdClass::class, $generic[0]);

        $this->setExpectedException(
            InvalidTypeException::class,
            'Type must be stdClass, but Generics\ArrayList was given.'
        );
        $generic[] = new ArrayList('integer');
    }

    public function testObjectValidatorInvalid()
    {
        $this->setExpectedException(
            InvalidTypeException::class,
            'This\Class\Does\Not\Exist is an invalid type or does not exist as a class.'
        );
        new ArrayList('This\Class\Does\Not\Exist');
    }
}