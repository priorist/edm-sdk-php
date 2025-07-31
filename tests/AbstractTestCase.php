<?php

declare(strict_types=1);

namespace Priorist\EDM\Test;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
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

        foreach ($eventBase['events'] as $event) {
            if (is_array($event)) {
                $this->assertIsEvent($event);
            }
        }
    }


    public function assertIsEvent($event): void
    {
        $this->assertIsArray($event);

        $this->assertArrayHasKey('id', $event);
        $this->assertArrayHasKey('status', $event);
        $this->assertArrayHasKey('is_public', $event);
        $this->assertArrayHasKey('files', $event);

        $this->assertIsInt($event['id']);
        $this->assertTrue($event['is_public'], 'Event is not public.');
        $this->assertContains($event['status'], ['TAKES_PLACE', 'OFFERED'], sprintf('Invalid event status: %s', $event['status']));
        $this->assertIsArray($event['files']);

        foreach ($event['files'] as $document) {
            $this->assertIsPublicDocument($document);
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
