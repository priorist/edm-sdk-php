<?php

namespace Priorist\EDM\Client\Rest;

use InvalidArgumentException;
use UnexpectedValueException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Priorist\EDM\Client\Collection;
use Priorist\EDM\Client\User;
use Priorist\EDM\Helper\ArrayHelper;

class EdmClient implements RestClient
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


    public function fetchSingle(string $endpoint, $idOrSlug, array $params) : ?array
    {
        return $this->fetch(sprintf('%s/%s', $endpoint, $idOrSlug), $params);
    }


    public function fetchCollection(string $endpoint, array $params) : Collection
    {
        return new Collection($this->query('GET', $endpoint, $params));
    }


    public function fetch(string $endpoint, array $params = []) : ?array
    {
        return $this->queryJson('GET', $endpoint, $params);
    }


    public function create(string $endpoint, array $data = [], array $params = []) : ?array
    {
        return $this->queryJson('POST', $endpoint, $params, $data);
    }


    public function queryJson(string $method, string $endpoint, array $params = [], array $body = null) : ?array
    {
        $result = $this->query($method, $endpoint, $params, $body);

        if ($result === null) {
            return null;
        }

        return static::decodeResponse($result);
    }


    public function query(string $method, string $endpoint, array $params = [], array $body = null) : ?string
    {
        $requestOptions = [
            'query' => static::prepareQueryParams($params)
        ];

        if ($body !== null) {
            $requestOptions['json'] = $body;
        }

        try {
            $requestOptions['headers']['Authorization'] = 'Bearer ' . $this->getAccessToken()->getToken();

            // Django requires a trailing slash to prevent redirects
            $response = $this->getHttpClient()->request($method, $endpoint . '/', $requestOptions);
        } catch (IdentityProviderException $e) {
            if ($e->getMessage() == 'invalid_client') {
                throw new InvalidArgumentException('Invalid client credentials.', 403, $e);
            }

            throw $e; // @codeCoverageIgnore
        } catch (GuzzleClientException $e) {
            return static::handleClientException($e);
        }

        return $response->getBody();
    }


    public function logIn(string $userName, string $password) : AccessToken
    {
        try {
            $accessToken = $this->getOAuthProvider()->getAccessToken('password', [
                'username' => $userName,
                'password' => $password,
            ]);
        } catch (IdentityProviderException $e) {
            throw new InvalidArgumentException(sprintf(
                'Invalid client credentials: %s',
                $e->getMessage()
            ), 403, $e);
        }

        $this->setAccessToken($accessToken);

        return $accessToken;
    }


    public function getUser() : User
    {
        return $this->getOAuthProvider()->getResourceOwner($this->getAccessToken());
    }


    public static function handleClientException(GuzzleClientException $e)
    {
        switch ($e->getCode()) {
            case 404:
                return null;
        }

        throw new ClientException($e);
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


    /**
     * Transforms parameters containing arrays to a string due to https://github.com/guzzle/guzzle/issues/1308#issuecomment-156816900
     *
     * @param array $params The query parameters
     *
     * @return array|string The prepared array or a query string
     */
    public static function prepareQueryParams(array $params)
    {
        if (ArrayHelper::containsArray($params)) {
            return static::cascadeQueryParams($params);
        } else {
            foreach ($params as &$param) {
                $param = trim($param);
            }
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
                $paramString .= '&' . urlencode(trim($key)) . '=' . urlencode(trim($value));
            }
        }

        // Strip first & and return
        return substr($paramString, 1);
    }


    public static function getDefaultRestClientoptions()
    {
        return [
            'headers' => [
                'User-Agent' => 'priorist/EDM/SDK PHP' . PHP_VERSION
            ]
        ];
    }


    public static function getBaseUri(string $edmUrl) : string
    {
        return sprintf('%s/api/v1/', trim($edmUrl, "/ \t\n\r\0\x0B"));
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
