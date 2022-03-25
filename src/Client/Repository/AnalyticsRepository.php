<?php

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Helper\AnalyticsHelper;

class AnalyticsRepository extends AbstractRepository
{
    protected string $userId;

    /**
     * Tracks an event with additional information.
     *
     * @param string    $trackingEvent  The type of event that should be tracked.
     * @param int       $eventBase      The ID of the EDM event base.
     * @param int       $event          Optional. The ID of the EDM event.
     * @param string    $userId         Optional. Unique identifier of a tracked user. Defaults to a hashed user id based on ip address and user agent.
     * @param string    $url            Optional. The current URL being tracked.
     * @param string    $referrer       Optional. The current URL referrer.
     * @param string    $utmMedium      Optional. The value to be used as 'utm_medium' query parameter. If not provided, EDM will try to parse it from URL.
     * @param string    $utmSource      Optional. The value to be used as 'utm_source' query parameter. If not provided, EDM will try to parse it from URL.
     * @param string    $utmCampaign    Optional. The value to be used as 'utm_campaign' query parameter. If not provided, EDM will try to parse it from URL.
     *
     * @return array|null The decoded response as array or NULL, if the JSON was malformed.
     */
    public function track(string $trackingEvent, int $eventBase, ?int $event = null, ?string $userId = null, ?string $url = null, ?string $referrer = null, ?string $utmMedium = null, ?string $utmSource = null, ?string $utmCampaign = null): ?array
    {
        $data = [
            "name" => $trackingEvent,
            "event_base" => $eventBase,
            "event" => $event,
            "userId" => $this->userId ?? $userId ?? AnalyticsHelper::getHashedUserId(),
            "url" => $url ?? AnalyticsHelper::getFullUrl(),
            "referrer" => $referrer ?? AnalyticsHelper::getReferrerUrl(),
            "utm_medium" => $utmMedium,
            "utm_source" => $utmSource,
            "utm_campaign" => $utmCampaign
        ];

        return $this->create($data);
    }

    /**
     * Tracks an event with additional information.
     *
     * @param string    $trackingEvent  The type of event that should be tracked.
     * @param int       $eventBase      The ID of the EDM event base.
     * @param int       $event          Optional. The ID of the EDM event.
     * @param string    $userId         Optional. Unique identifier of a tracked user. Defaults to a hashed user id based on ip address and user agent.
     * @param string    $url            Optional. The current URL being tracked.
     * @param string    $referrer       Optional. The current URL referrer.
     * @param string    $utmMedium      Optional. The value of 'utm_medium' query parameter.
     * @param string    $utmSource      Optional. The value of 'utm_source' query parameter.
     * @param string    $utmCampaign    Optional. The value of 'utm_campaign' query parameter.
     *
     * @return array|null The decoded response as array or NULL, if the JSON was malformed.
     */
    public function trackSilently(string $trackingEvent, int $eventBase, ?int $event = null, ?string $userId = null, ?string $url = null, ?string $referrer = null, ?string $utmMedium = null, ?string $utmSource = null, ?string $utmCampaign = null): ?array
    {
        try {
            return $this->track($trackingEvent, $eventBase, $event, $userId, $url, $referrer, $utmMedium, $utmSource, $utmCampaign);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Sets a custom user Id.
     *
     * @param string $userId Custom user id.
     * @return $this
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;

        return $this;
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
