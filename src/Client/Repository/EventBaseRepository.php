<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventBaseRepository extends AbstractSearchableRepository
{
    /**
     * Returns a single event base having child relations expanded.
     *
     * @param int $id The unique ID of the event base
     *
     * @return array The event base as array or NULL, if matching event base was not found
     */
    public function findById(int $id, array $params = []) : ?array
    {
        return $this->fetchSingle($id, ['expand' => '~all,events.location,events.lecturers'], $params);
    }


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
