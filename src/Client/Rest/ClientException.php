<?php
namespace Priorist\AIS\Client\Rest;

use InvalidArgumentException;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Priorist\AIS\Client\Rest\AisClient;


class ClientException extends InvalidArgumentException
{
    protected $details = [];


    public function __construct(GuzzleClientException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);

        $this->setDetails(AisClient::decodeResponse($e->getResponse()->getBody()));
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
