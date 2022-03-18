<?php

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Helper\AnalyticsHelper;

class AnalyticsRepository extends AbstractRepository
{
    protected string $userId;
    protected string $url;
    protected string $referrer;
    protected string $utmMedium;
    protected string $utmSource;
    protected string $utmCampaign;

    /**
     * Tracks an event with additional information.
     *
     * @param string    $trackingEvent  The type of event that should be tracked.
     * @param int       $event          The ID of the EDM event.
     * @param int       $eventBase      The ID of the EDM event base.
     * @param array     $contexts       The contexts of the EDM event base.
     * @param string    $userId         Optional. Unique identifier of a tracked user. Defaults to a hashed user id based on ip address and user agent.
     * @param string    $url            Optional. The current URL being tracked.
     * @param string    $referrer       Optional. The current URL referrer.
     * @param string    $utmMedium      Optional. The value of 'utm_medium' query parameter.
     * @param string    $utmSource      Optional. The value of 'utm_source' query parameter.
     * @param string    $utmCampaign    Optional. The value of 'utm_campaign' query parameter.
     *
     * @return array|null The decoded response as array or NULL, if the JSON was malformed.
     */
    public function track(string $trackingEvent, int $event, int $eventBase, array $contexts, ?string $userId = "", ?string $url = "", ?string $referrer = "", ?string $utmMedium = "", ?string $utmSource = "", ?string $utmCampaign = ""): ?array
    {
        $data = [
            "name" => $trackingEvent,
            "event" => $event,
            "event_base" => $eventBase,
            "contexts" => $contexts,
            "userId" => $this->userId ?? $userId ?? AnalyticsHelper::getHashedUserId(),
            "url" => $this->url ?? $url,
            "referrer" => $this->referrer ?? $referrer,
            "utm_medium" => $this->utmMedium ?? $utmMedium,
            "utm_source" => $this->utmSource ?? $utmSource,
            "utm_campaign" => $this->utmCampaign ?? $utmCampaign
        ];

        return $this->create($data);
    }

    /**
     * Tracks an event. For additional information, opinionated defaults are used.
     *
     * @param string    $trackingEvent  The type of event that should be tracked.
     * @param int       $event          The ID of the EDM event.
     * @param int       $eventBase      The ID of the EDM event base.
     * @param array     $contexts       The contexts of the EDM event base.
     *
     * @return array|null The decoded response as array or NULL, if the JSON was malformed.
     */
    public function trackWithDefaults(string $trackingEvent, int $event, int $eventBase, array $contexts)
    {
        $userId = $this->userId ?? AnalyticsHelper::getHashedUserId();
        $url = $this->url ?? AnalyticsHelper::getFullUrl();
        $referrer = $this->referrer ?? AnalyticsHelper::getReferrerUrl();
        $utmMedium = $this->utmMedium ?? AnalyticsHelper::getUtmParameter('medium');
        $utmSource = $this->utmSource ?? AnalyticsHelper::getUtmParameter('source');
        $utmCampaign = $this->utmCampaign ?? AnalyticsHelper::getUtmParameter('campaign');

        return $this->track($trackingEvent, $event, $eventBase, $contexts, $userId, $url, $referrer, $utmMedium, $utmSource, $utmCampaign);
    }

    /**
     * Sets the value to be tracked for 'utm_medium' parameter.
     *
     * @param string $utmMedium Value of 'utm_medium' parameter.
     * @return $this
     */
    public function setUtmMedium(string $utmMedium)
    {
        $this->utmMedium = $utmMedium;

        return $this;
    }

    /**
     * Sets the value to be tracked for 'utm_source' parameter.
     *
     * @param string $utmSource Value of 'utm_source' parameter.
     * @return $this
     */
    public function setUtmSource(string $utmSource)
    {
        $this->utmSource = $utmSource;

        return $this;
    }

    /**
     * Sets the value to be tracked for 'utm_campaign' parameter.
     *
     * @param string $utmCampaign Value of 'utm_campaign' parameter.
     * @return $this
     */
    public function setUtmCampaign(string $utmCampaign)
    {
        $this->utmCampaign = $utmCampaign;

        return $this;
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

    /**
     * Sets the current URL referrer
     *
     * @param string $url Raw URL (not URL encoded)
     * @return $this
     */
    public function setReferrer(string $url)
    {
        $this->referrer = $url;

        return $this;
    }

    /**
     * Sets the current URL being tracked
     *
     * @param string $url Raw URL (not URL encoded)
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

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
