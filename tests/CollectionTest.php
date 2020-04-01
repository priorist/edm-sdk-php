<?php

use PHPUnit\Framework\TestCase;

use Priorist\AIS\Client\Client;
use Priorist\AIS\Client\Collection;


class CollectionTest extends TestCase
{
    public function testStructure()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $collection = $client->event->findUpcoming();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertGreaterThanOrEqual(0, $collection->count());
        $this->assertIsArray($collection->toArray());
        $this->assertIsString($collection->serialize());

        if (!$collection->hasItems()) {
            $this->markTestSkipped('No items returned.');
        }

        $this->assertEquals(0, $collection->key());

        return $collection;
    }


    /**
     * @depends testStructure
     */
    public function testArrayAccess(Collection $collection)
    {
        $this->assertEquals(count($collection), $collection->count());
        $this->assertIsArray($collection[0]);
        $this->assertNull($collection[-1]);

        $this->expectException(BadMethodCallException::class);
        $collection[0] = array();
    }


    /**
     * @depends testStructure
     */
    public function testReadOnly(Collection $collection)
    {
        $this->expectException(BadMethodCallException::class);
        unset($collection[0]);
    }


    public function testInvalidJson()
    {
        $this->expectException(UnexpectedValueException::class);
        new Collection('{');
    }


    public function testInvalidFormat()
    {
        $this->expectException(UnexpectedValueException::class);
        new Collection('"Huhu"');
    }


    public function testInvalidData()
    {
        $this->expectException(UnexpectedValueException::class);
        new Collection('{"foo": "bar"}');
    }
}
