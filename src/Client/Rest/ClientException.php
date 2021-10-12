<?php
namespace Priorist\EDM\Client\Rest;

use InvalidArgumentException;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Priorist\EDM\Client\Rest\EdmClient;


class ClientException extends InvalidArgumentException
{
    protected $details = [];


    public function __construct(GuzzleClientException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);

        $this->setDetails(EdmClient::decodeResponse($e->getResponse()->getBody()));
    }


    public function setDetails(array $details) : ClientException
    {
        $this->details = $details;

        return $this;
    }


    public function getDetails() : array
    {
        return $this->details;
    }
}
