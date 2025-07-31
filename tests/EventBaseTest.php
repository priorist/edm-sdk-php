<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Collection;


class EventBaseTest extends TestCase
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


    public function assertIsEventBase($eventBase): void
    {
        $this->assertIsArray($eventBase);

        $this->assertArrayHasKey('id', $eventBase);
        $this->assertArrayHasKey('name', $eventBase);
        $this->assertArrayHasKey('slug', $eventBase);
        $this->assertArrayHasKey('events', $eventBase);
        $this->assertArrayHasKey('documents', $eventBase);

        $this->assertIsInt($eventBase['id']);
        $this->assertIsArray($eventBase['events']);
        $this->assertIsArray($eventBase['documents']);

        foreach ($eventBase['documents'] as $document) {
            $this->assertIsPublicDocument($document);
        }

        // Check if legacy files are present. Will be removed in the future. Replaced by documents.
        if (array_key_exists('files', $eventBase)) {
            $this->assertIsArray($eventBase['files']);

            foreach ($eventBase['files'] as $document) {
                $this->assertIsPublicDocument($document);
            }
        }
    }


    public function assertIsPublicDocument($document): void
    {
        $this->assertIsArray($document);

        $this->assertArrayHasKey('id', $document);
        $this->assertArrayHasKey('visible_for_all', $document);
        $this->assertArrayHasKey('url', $document);
        $this->assertArrayHasKey('name', $document);

        $this->assertIsString($document['id']);
        $this->assertIsBool($document['visible_for_all']);
        $this->assertIsString($document['url']);

        $this->assertTrue($document['visible_for_all'], sprintf('Document %s (ID: %s) is not visible for all', $document['name'], $document['id']));
    }
}
