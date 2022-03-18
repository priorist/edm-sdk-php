<?php

use PHPUnit\Framework\TestCase;

use Priorist\EDM\Client\Client;
use Priorist\EDM\Helper\AnalyticsHelper;

class AnalyticsTest extends TestCase
{
    public function testTrack()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));
        $analytics = $client->analytics;

        $trackingEvent = "event_viewed";
        $event = 1;
        $eventBase = 1;
        $contexts = [1, 2];

        $analytics->setUserId(AnalyticsHelper::getHashedUserId());
        $analytics->setUrl(AnalyticsHelper::getFullUrl());
        $analytics->setReferrer(AnalyticsHelper::getReferrerUrl());
        $analytics->setUtmMedium(AnalyticsHelper::getUtmParameter('medium'));
        $analytics->setUtmSource(AnalyticsHelper::getUtmParameter('source'));
        $analytics->setUtmCampaign(AnalyticsHelper::getUtmParameter('campaign'));

        $response = $client->analytics->track($trackingEvent, $event, $eventBase, $contexts);

        $this->assertIsArray($response);
    }

    public function testTrackWithDefaults()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $trackingEvent = "event_viewed";
        $event = 1;
        $eventBase = 1;
        $contexts = [1, 2];

        $response = $client->analytics->trackWithDefaults($trackingEvent, $event, $eventBase, $contexts);

        $this->assertIsArray($response);
    }
}
