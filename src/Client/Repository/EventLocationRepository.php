<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class EventLocationRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath(): string
    {
        return 'event_locations';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'name';
    }
}
