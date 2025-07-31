<?php

declare(strict_types=1);

namespace Priorist\EDM\Test;

use Priorist\EDM\Client\Client;

class AnalyticsTest extends AbstractTestCase
{
    public function testTrack()
    {
        if (php_sapi_name() === 'cli') {
            $this->markTestSkipped('This test requires a web server to run.');
        }

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $trackingEvent = 'event_viewed';
        $event = 1;
        $eventBase = 1;
        $context = 1;

        $response = $client->analytics->trackSilently($trackingEvent, $eventBase, $event, $context);

        $this->assertIsArray($response);
    }
}
