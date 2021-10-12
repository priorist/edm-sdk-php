<?php
namespace Priorist\EDM\OAuth2\Client\Provider;

use BadMethodCallException;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

class EdmProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;


    /**
     * @var string Base URL of the EDM instance
     */
    protected $baseUrl;


    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        throw new BadMethodCallException('Grant type authcode not supported by EDM.');
    }


    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseUrl . 'o/token/';
    }


    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->baseUrl . 'me/';
    }


    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['read write'];
    }


    /**
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error']) && !empty($data['error'])) {
            throw new IdentityProviderException($data['error'], 4711, $response);
        }
    }


    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param  array $response
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        if (isset($response['id']) && is_int($response['id']) && $response['id'] > 0) {
            return new EdmResourceOwner($response, intval($response['id']));
        }

        throw new UnexpectedValueException(sprintf(
            'Invalid resource owner reponse: %s',
            $response
        ));
    }
}
