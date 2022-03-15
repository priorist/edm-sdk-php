<?php

use PHPUnit\Framework\TestCase;

use Priorist\EDM\Client\Client;
use Priorist\EDM\Helper\AnalyticsHelper;


class AnalyticsTest extends TestCase
{
    public function testTrack()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $trackingEvent = "event_viewed";
        $event = 1;
        $eventBase = 1;
        $url = AnalyticsHelper::getFullUrl();
        $referrer = AnalyticsHelper::getReferrerUrl();
        $userId = AnalyticsHelper::getHashedUserId();
        $contexts = [1, 2];
        $utmParameters = AnalyticsHelper::getUtmParameters();
        $utmMedium = $utmParameters["utm_medium"];
        $utmSource = $utmParameters["utm_source"];
        $utmCampaign = $utmParameters["utm_campaign"];

        $response = $client->analytics->track($trackingEvent, $event, $eventBase, $url, $referrer, $userId, $contexts, $utmMedium, $utmSource, $utmCampaign);

        $this->assertIsArray($response);

        return $response;
    }
}
