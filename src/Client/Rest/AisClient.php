<?php
namespace Priorist\AIS\Client\Rest;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use UnexpectedValueException;

use GuzzleHttp\Client as HttpClient;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

use Priorist\AIS\Client\Collection;
use Priorist\AIS\Helper\ArrayHelper;


class AisClient implements RestClient
{
    protected AbstractProvider $oauthProvider;
    protected HttpClient $httpClient;
    protected ?AccessToken $accessToken = null;


    public function __construct(string $baseUrl, AbstractProvider $oauthProvider, array $options = [])
    {
        $this->setOAuthProvider($oauthProvider);

        $this->setHttpClient(
            new HttpClient(
                array_merge_recursive(static::getDefaultRestClientoptions(), $options, [
                    'base_uri' => static::getBaseUri($baseUrl)
                ])
            )
        );
    }


    public function querySingle(string $endpoint, int $id, array $params) : ?array
    {
        return $this->query(sprintf('%s/%u', $endpoint, $id), $params);
    }


    public function queryCollection(string $endpoint, array $params) : Collection
    {
        return new Collection($this->queryRaw($endpoint, $params));
    }


    public function query(string $endpoint, array $params = []) : ?array
    {
        $result = $this->queryRaw($endpoint, $params);

        if ($result === null) {
            return null;
        }

        return static::decodeResponse($result);
    }


    public function queryRaw(string $endpoint, array $params = []) : ?string
    {
        try {
            $response = $this->getHttpClient()->get($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken()->getToken(),
                ],
                'query' => static::pepareQueryParams($params)
            ]);
        } catch (IdentityProviderException $e) {
            if ($e->getMessage() == 'invalid_client') {
                throw new InvalidArgumentException('Invalid client credentials.');
            }

            return static::handleException($e); // @codeCoverageIgnore
        } catch (Exception $e) {
            return static::handleException($e);
        }

        return $response->getBody();
    }


    public static function decodeResponse(string $response) : array
    {
        $data = json_decode($response, true, 16, JSON_BIGINT_AS_STRING);

        if ($data === null) {
            throw new UnexpectedValueException('Invalid or malformed JSON.');
        }

        if (!is_array($data)) {
            throw new UnexpectedValueException('Array expected from JSON source.');
        }

        return $data;
    }


    public static function handleException(Exception $e)
    {
        switch ($e->getCode()) {
            case 401:
                throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
            case 404:
                return null;
        }

        throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
    }


    /**
     * Transforms parameters containing arrays to a string due to https://github.com/guzzle/guzzle/issues/1308#issuecomment-156816900
     *
     * @param array $params The query parameters
     *
     * @return array|string The prepared array or a query string
     */
    public static function pepareQueryParams(array $params)
    {
        if (ArrayHelper::containsArray($params)) {
            return static::cascadeQueryParams($params);
        }

        return $params;
    }


    public static function cascadeQueryParams(array $params, string $commonName = null) : string
    {
        $paramString = '';
        foreach ($params as $key => &$value) {
            if ($commonName !== null) {
                $key = $commonName;
            }

            if (is_array($value)) {
                $paramString .= '&' . static::cascadeQueryParams($value, $key);
            } else {
                $paramString .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }

        // Strip first & and return
        return substr($paramString, 1);
    }


    public static function getDefaultRestClientoptions()
    {
        return [
            'headers' => [
                'User-Agent' => 'priorist/AIS/SDK PHP' . PHP_VERSION
            ]
        ];
    }


    public static function getBaseUri(string $aisUrl) : string
    {
        return sprintf('%s/api/v1/', trim($aisUrl, "/ \t\n\r\0\x0B"));
    }


    public function setOAuthProvider(AbstractProvider $oauthProvider) : RestClient
    {
        $this->oauthProvider = $oauthProvider;

        return $this;
    }


    public function getOAuthProvider() : AbstractProvider
    {
        return $this->oauthProvider;
    }


    public function setHttpClient(HttpClient $httpClient) : RestClient
    {
        $this->httpClient = $httpClient;

        return $this;
    }


    public function getHttpClient() : HttpClient
    {
        return $this->httpClient;
    }


    public function setAccessToken(AccessToken $accessToken) : RestClient
    {
        $this->accessToken = $accessToken;

        return $this;
    }


    public function getAccessToken() : AccessToken
    {
        if ($this->accessToken === null) {
            $this->setAccessToken($this->getOAuthProvider()->getAccessToken('client_credentials'));
        }

        return $this->accessToken;
    }
}
