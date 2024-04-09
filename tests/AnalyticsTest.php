<?php

use PHPUnit\Framework\TestCase;

use Priorist\EDM\Client\Client;

class AnalyticsTest extends TestCase
{
    public function testTrack()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $trackingEvent = 'event_viewed';
        $event = 1;
        $eventBase = 1;
        $context = 1;

        $response = $client->analytics->trackSilently($trackingEvent, $eventBase, $event, $context);

        $this->assertIsArray($response);
    }
}
