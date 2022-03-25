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
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

        $fullUrl = sprintf('%s://%s%s', $protocol, $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);

        return $fullUrl;
    }

    /**
     * Get the full URL of the referring page.
     *
     * @return string The HTTP referrer or NULL, if none was set.
     */
    public static function getReferrerUrl(): ?string
    {
        $refUrl = null;

        if (isset($_SERVER['HTTP_REFERRER']) && !empty($server['HTTP_REFERER'])) {
            $refUrl = $server['HTTP_REFERER'];
        }

        return $refUrl;
    }

    /**
     * Get a SHA-256 hashed user id based on ip address and user agent
     *
     * @return string The hashed user id or NULL, if no ip address or user agent is available.
     */
    public static function getHashedUserId(): ?string
    {
        $hashedUserId = null;
        $userAgent = self::getUserAgent();
        $ipAddress = self::getIpAddress();

        if (!empty($userAgent) || !empty($ipAddress)) {
            $hashedUserId = hash('sha256', $ipAddress . $userAgent);
        }

        return $hashedUserId;
    }

    protected static function getIpAddress(): ?string
    {
        $ip = null;

        if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    protected static function getUserAgent(): ?string
    {
        $userAgent = null;

        if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($server['HTTP_USER_AGENT'])) {
            $userAgent = $server['HTTP_USER_AGENT'];
        }

        return $userAgent;
    }
}
