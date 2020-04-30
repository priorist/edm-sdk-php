<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventBaseRepository extends AbstractSearchableRepository
{
    /**
     * Returns a collection of all event bases including their event records
     *
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of event bases
     */
    public function findAllWithEvents(array $params = []) : Collection
    {
        return $this->fetchCollection([
            'expand' => 'events',
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'event_bases';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'name';
    }
}
