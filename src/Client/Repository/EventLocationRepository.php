<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventLocationRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath() : string
    {
        return 'event_locations';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'name';
    }
}
