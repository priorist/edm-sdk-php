<?php

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Helper\AnalyticsHelper;

class AnalyticsRepository extends AbstractRepository
{
    /**
     * Tracks an event with additional information.
     *
     * @param string $event The type of event that should be tracked.
     * @param array $data Additional data that should be sent in the POST body.
     *
     * @return array The decoded response as array or NULL, if the JSON was malformed.
     */
    public function track(string $trackingEvent, int $event, int $eventBase, ?string $url, ?string $referrer, ?string $userId, ?array $contexts, ?string $utmMedium, ?string $utmSource, ?string $utmCampaign): ?array
    {
        $data = [
            "name" => $trackingEvent,
            "event" => $event,
            "event_base" => $eventBase,
            "url" => $url,
            "referrer" => $referrer,
            "contexts" => $contexts,
            "utm_medium" => $utmMedium,
            "utm_source" => $utmSource,
            "utm_campaign" => $utmCampaign
        ];

        if (!isset($userId)) {
            $data["userId"] = AnalyticsHelper::getHashedUserId();
        }

        return $this->create($data);
    }

    public static function getEndpointPath(): string
    {
        return "analytics";
    }

    protected static function getDefaultOrdering(): string
    {
        return "name";
    }
}
