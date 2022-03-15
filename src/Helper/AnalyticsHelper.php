<?php

namespace Priorist\EDM\Helper;

class AnalyticsHelper
{
    /**
     * Get the full URL of the page the request was made from.
     *
     * @return string The full URL with protocol, host, path, query and fragment.
     */
    public static function getFullUrl(): string
    {
        return (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Get the full URL of the referring page.
     *
     * @return string The HTTP referrer or NULL, if none was set.
     */
    public static function getReferrerUrl(): ?string
    {
        if (!empty($_SERVER["HTTP_REFERER"])) {
            $refUrl = $_SERVER["HTTP_REFERER"];
        } else {
            $refUrl = null;
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
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? null;
        $ipAddress = $_SERVER["HTTP_CLIENT_IP"]
            ?: ($_SERVER["HTTP_X_FORWARDED_FOR"]
                ?: $_SERVER["REMOTE_ADDR"]);

        $hashedUserId = hash("sha256", $ipAddress . $userAgent);

        return $hashedUserId;
    }

    /**
     * Get the value of all UTM parameteres.
     *
     * @return array The value of all UTM parameters or NULL, if none are set.
     */
    public static function getUtmParameters(): ?array
    {
        $utmParameterValues = [];
        $utmParameters = [
            "utm_medium",
            "utm_source",
            "utm_campaign"
        ];

        if (!empty($_GET)) {
            foreach ($utmParameters as $utmParameter) {
                if (isset($_GET[$utmParameter])) {
                    $utmParameterValues[$utmParameter] = $_GET[$utmParameter];
                } else {
                    $utmParameterValues[$utmParameter] = null;
                }
            }
        }

        return $utmParameterValues;
    }

    /**
     * Get the value of an UTM parameter.
     *
     * @param string $type The UTM parameter type: medium, source, campaign, term or content.
     *
     * @return string The value of the UTM parameter or NULL, if none was set.
     */
    public static function getUtmParameter(string $type): ?string
    {
        if (isset($_GET["utm_" . $type])) {
            $utmParameter = $_GET["utm_" . $type];
        } else {
            $utmParameter = null;
        }

        return $utmParameter;
    }
}
