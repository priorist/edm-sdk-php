<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Rest;

use InvalidArgumentException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Priorist\EDM\Client\Rest\EdmClient;

class ClientException extends InvalidArgumentException
{
    protected array $details = [];


    public function __construct(GuzzleClientException $e)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e);

        $this->setDetails(EdmClient::decodeResponse((string) $e->getResponse()->getBody()));
    }


    public function setDetails(array $details): self
    {
        $this->details = $details;

        return $this;
    }


    public function getDetails(): array
    {
        return $this->details;
    }
}
