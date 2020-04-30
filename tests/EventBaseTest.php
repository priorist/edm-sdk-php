<?php

use PHPUnit\Framework\TestCase;

use Priorist\AIS\Client\Client;
use Priorist\AIS\Client\Collection;


class EventBaseTest extends TestCase
{
    public function testList()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $eventBases = $client->eventBase->findAllWithEvents();

        $this->assertInstanceOf(Collection::class, $eventBases);
        $this->assertGreaterThanOrEqual(0, $eventBases->count());

        if (!$eventBases->hasItems()) {
            $this->markTestSkipped('No event bases returned.');
        }

        foreach ($eventBases as $eventBase) {
            $this->assertIsArray($eventBase);
            $this->assertIsInt($eventBase['id']);
            $this->assertIsArray($eventBase['events']);
        }

        $this->assertNull($eventBases->current());

        $eventBases->rewind();

        return $eventBases;
    }


    /**
     * @depends testList
     */
    public function testSingle(Collection $eventBases)
    {
        $this->assertIsArray($eventBases->current());
        $this->assertArrayHasKey('id', $eventBases->current());

        $existingEventBaseId = $eventBases->current()['id'];

        $this->assertIsInt($existingEventBaseId);

        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->eventBase->findById(0));

        $eventBase = $client->eventBase->findById($existingEventBaseId);

        $this->assertIsArray($eventBase);
        $this->assertArrayHasKey('id', $eventBase);
        $this->assertEquals($existingEventBaseId, $eventBase['id']);

        $this->assertArrayHasKey('events', $eventBase);
        $this->assertIsArray($eventBase['events']);

        return $eventBase;
    }


    /**
     * @depends testSingle
     */
    public function testSearch(array $eventBase)
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $eventBases = $client->eventBase->findBySearchPhrase($eventBase['name']);

        $this->assertInstanceOf(Collection::class, $eventBases);
        $this->assertGreaterThan(0, $eventBases->count());
    }
}
