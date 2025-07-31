<?php

declare(strict_types=1);

namespace Priorist\EDM\Test;

use PHPUnit\Framework\Attributes\Depends;
use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Collection;

class EventBaseTest extends AbstractTestCase
{
    public function testList()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $eventBases = $client->eventBase->findAllWithEvents();

        $this->assertInstanceOf(Collection::class, $eventBases);
        $this->assertGreaterThanOrEqual(0, $eventBases->count());

        if (!$eventBases->hasItems()) {
            $this->markTestSkipped('No event bases returned.');
        }

        foreach ($eventBases as $eventBase) {
            $this->assertIsEventBase($eventBase);
        }

        $this->assertNull($eventBases->current());

        $eventBases->rewind();

        return $eventBases;
    }


    #[Depends('testList')]
    public function testSingleById(Collection $eventBases)
    {
        $this->assertIsArray($eventBases->current());
        $this->assertArrayHasKey('id', $eventBases->current());

        $existingEventBaseId = $eventBases->current()['id'];

        $this->assertIsInt($existingEventBaseId);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->eventBase->findById(0));

        $eventBase = $client->eventBase->findById($existingEventBaseId);

        $this->assertIsEventBase($eventBase);
        $this->assertEquals($existingEventBaseId, $eventBase['id']);

        return $eventBase;
    }


    #[Depends('testSingleById')]
    public function testSingleBySlug(array $eventBase)
    {
        $this->assertArrayHasKey('slug', $eventBase);

        $existingEventBaseId = $eventBase['id'];

        $this->assertIsInt($existingEventBaseId);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->eventBase->findBySlug('abcdefghijklmnopqrstuvwxyz1234567890'));

        $eventBase = $client->eventBase->findBySlug(trim($eventBase['slug']));

        $this->assertIsEventBase($eventBase);
        $this->assertEquals($existingEventBaseId, $eventBase['id']);

        return $eventBase;
    }


    #[Depends('testSingleById')]
    public function testSearch(array $eventBase)
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $eventBases = $client->eventBase->findBySearchPhrase($eventBase['name']);

        $this->assertInstanceOf(Collection::class, $eventBases);
        $this->assertGreaterThan(0, $eventBases->count());
    }
}
