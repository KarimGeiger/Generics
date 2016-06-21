<?php

namespace Tests;

use Generics\ArrayList;
use Generics\Exceptions\InvalidTypeException;
use Generics\Dictionary;

class BasicTest extends TestCase
{
    public function testDefaultScenario()
    {
        $generic = new Dictionary('integer', 'string', [1 => 'bar']);
        $this->assertEquals(['integer', 'string'], $generic->getTypes());

        $generic->put(2, 'foo');
        $generic->put(3, 'baz');

        $this->assertEquals([1 => 'bar', 2 => 'foo', 3 => 'baz'], $generic->toArray());

        $this->assertEquals('baz', $generic->pull(3));
        $this->assertCount(2, $generic);

        $generic[100] = 'something';
        $this->assertCount(3, $generic);

        $this->assertTrue($generic->has('something'));
        $this->assertFalse($generic->has('something_not'));
        $this->assertTrue(isset($generic[100]));
    }

    public function testArrayBehaviour()
    {
        $generic = new Dictionary('string', 'string', ['foo' => 'foo', 'bar' => 'bar']);

        $data = ['foo' => 'foo', 'bar' => 'bar'];
        foreach ($generic as $k => $v) {
            $this->assertEquals($data[$k], $v);
        }

        $generic->rewind();
        $this->assertEquals('bar', $generic->next());
        $this->assertEquals('bar', $generic->current());
        $this->assertEquals('bar', $generic->key());
        $this->assertEquals('foo', $generic->previous());

        $generic->merge(['with' => 'some', 'more' => 'data', 'better' => 'tests']);

        $newData = [];
        while ($generic->valid()) {
            $newData[] = $generic->current();
            $generic->next();
        }

        $this->assertEquals(array_values($generic->toArray()), $newData);

        $this->assertEquals('some', $generic['with']);
        $this->assertCount(5, $generic);
    }

    public function testSerializationAndToString()
    {
        $expected = [
            1 => 'bar',
            2 => 'foo',
            3 => 'some',
            4 => 'more',
            5 => 'data'
        ];
        $generic = new Dictionary('integer', 'string', $expected);
        $new = unserialize(serialize($generic));
        $this->assertInstanceOf(Dictionary::class, $new);
        $this->assertEquals($expected, $new->toArray());

        $this->assertEquals('{"1":"bar","2":"foo","3":"some","4":"more","5":"data"}', (string)$generic);
    }

    public function testMergeSelf()
    {
        $generic = new Dictionary('string', 'string', ['foo' => 'bar']);
        $generic->merge(new Dictionary('string', 'string', ['baz' => 'foo']));
        $this->assertEquals(['foo' => 'bar', 'baz' => 'foo'], $generic->toArray());
    }

    public function testRecursion()
    {
        $generic = new ArrayList(ArrayList::class);
        $generic[] = new ArrayList('string');
        $generic[0][] = 'foo';

        $this->assertEquals('foo', $generic[0][0]);
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but integer was given.');
        $generic[0][] = 1;
    }

    public function testArrayList()
    {
        $generic = new ArrayList('string', ['foo', 'bar']);
        $generic[] = 'baz';
        $generic[1] = 'foo';
        $generic->push('bar')[1] = 'baz';

        $this->assertEquals(['foo', 'baz', 'baz', 'bar'], $generic->toArray());
        $this->setExpectedException(InvalidTypeException::class, 'Type must be string, but double was given.');
        $generic->push(1.5);
    }
}