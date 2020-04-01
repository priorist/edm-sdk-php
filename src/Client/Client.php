<?php
namespace Priorist\AIS\Client;

use InvalidArgumentException;

use League\OAuth2\Client\Token\AccessToken;

use Priorist\AIS\Client\Rest\RestClient;
use Priorist\AIS\Client\Rest\AisClient;
use Priorist\AIS\Client\Repository\Repository;
use Priorist\AIS\OAuth2\Client\Provider\AisProvider;


class Client
{
    protected RestClient $restClient;
    protected array $repositories = [];


    public function __construct(string $aisUrl, string $clientId, string $clientSecret)
    {
        $this->setRestClient(
            new AisClient(
                $aisUrl,
                new AisProvider([
                    'baseUrl' => AisClient::getBaseUri($aisUrl),
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ])
            )
        );
    }


    /**
     * Magic methid to retreive repositories.
     *
     * @param string $name The name of the repository to fetch
     *
     * @throws InvalidArgumentException if no property with the provided name exists.
     *
     * @return Repository The repository matching the requested name
     */
    public function __get($name) : Repository
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        $repositoryClass = sprintf('Priorist\AIS\Client\Repository\%sRepository', ucfirst($name));

        if (class_exists($repositoryClass)) {
            $this->repositories[$name] = new $repositoryClass($this->getRestClient());

            return $this->repositories[$name];
        }

        throw new InvalidArgumentException(sprintf('%sRepository does not exist.', ucfirst($name)));
    }


    public function setAccessToken(AccessToken $accessToken) : Client
    {
        $this->getRestClient()->setAccessToken($accessToken);

        return $this;
    }


    public function getAccessToken() : AccessToken
    {
        return $this->getRestClient()->getAccessToken();
    }


    public function setRestClient(RestClient $restClient) : Client
    {
        $this->restClient = $restClient;

        return $this;
    }


    public function getRestClient() : RestClient
    {
        return $this->restClient;
    }
}
