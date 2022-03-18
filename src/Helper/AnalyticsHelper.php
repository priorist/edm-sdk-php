<?php

namespace Priorist\EDM\Helper;

class AnalyticsHelper
{
    /**
     * Get the full URL of the page the request was made from.
     *
     * @return string The full URL with protocol, host, path, query and fragment.
     */
    public static function getFullUrl(?array $server = null): string
    {
        $server ??= $_SERVER;
        $fullUrl = "";

        if (isset($server)) {
            $protocol = (isset($server["HTTPS"]) && $server["HTTPS"] === "on") ? "https" : "http";

            $fullUrl = $protocol . "://$server[HTTP_HOST]$server[REQUEST_URI]";
        }

        return $fullUrl;
    }

    /**
     * Get the full URL of the referring page.
     *
     * @return string The HTTP referrer or "", if none was set.
     */
    public static function getReferrerUrl(?array $server = null): string
    {
        $server ??= $_SERVER;
        $refUrl = "";

        if (!empty($server["HTTP_REFERER"])) {
            $refUrl = $server["HTTP_REFERER"];
        }

        return $refUrl;
    }

    /**
     * Get a SHA-256 hashed user id based on ip address and user agent
     *
     * @return string The hashed user id.
     */
    public static function getHashedUserId(): string
    {
        $hashedUserId = "";
        $userAgent = self::getUserAgent();
        $ipAddress = self::getIpAddress();

        if (!empty($userAgent) || !empty($ipAddress)) {
            $hashedUserId = hash("sha256", $ipAddress . $userAgent);
        }

        return $hashedUserId;
    }

    /**
     * Get the value of an UTM parameter.
     *
     * @param string $type The UTM parameter type: medium, source, campaign, term or content.
     *
     * @return string The value of the UTM parameter or "", if none was set.
     */
    public static function getUtmParameter(string $type): string
    {
        $utmParameter = "";

        if (isset($_GET["utm_" . $type])) {
            $utmParameter = $_GET["utm_" . $type];
        }

        return $utmParameter;
    }

    protected static function getIpAddress(?array $server = null): ?string
    {
        $server ??= $_SERVER;

        if (!empty($server["REMOTE_ADDR"])) {
            $ip = $server["REMOTE_ADDR"];
        }

        return $ip;
    }

    protected static function getUserAgent(?array $server = null): ?string
    {
        $server ??= $_SERVER;

        if (!empty($server["HTTP_USER_AGENT"])) {
            $userAgent = $server["HTTP_USER_AGENT"];
        }

        return $userAgent;
    }
}
