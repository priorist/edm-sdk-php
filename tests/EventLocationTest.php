<?php

use PHPUnit\Framework\TestCase;

use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Collection;


class EventLocationTest extends TestCase
{
    public function testList()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $locations = $client->eventLocation->findAll();

        $this->assertInstanceOf(Collection::class, $locations);
        $this->assertGreaterThanOrEqual(0, $locations->count());

        if (!$locations->hasItems()) {
            $this->markTestSkipped('No locations returned.');
        }

        foreach ($locations as $location) {
            $this->assertIsArray($location);
            $this->assertIsInt($location['id']);
        }

        $this->assertNull($locations->current());

        $locations->rewind();

        return $locations;
    }


    /**
     * @depends testList
     */
    public function testSingle(Collection $locations)
    {
        $this->assertIsArray($locations->current());
        $this->assertArrayHasKey('id', $locations->current());

        $existingLocationId = $locations->current()['id'];

        $this->assertIsInt($existingLocationId);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->eventLocation->findById(0));

        $location = $client->eventLocation->findById($existingLocationId);

        $this->assertIsArray($location);
        $this->assertArrayHasKey('id', $location);
        $this->assertEquals($existingLocationId, $location['id']);

        $this->assertArrayHasKey('rooms', $location);
        $this->assertIsArray($location['rooms']);

        return $location;
    }


    /**
     * @depends testSingle
     */
    public function testSearch(array $location)
    {
        $this->assertArrayHasKey('name', $location);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $locations = $client->eventLocation->findBySearchPhrase($location['name']);

        $this->assertInstanceOf(Collection::class, $locations);
        $this->assertGreaterThan(0, $locations->count());
    }
}
