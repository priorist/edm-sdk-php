<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventLocationRepository extends AbstractRepository
{
    /**
     * Returns a single event location.
     *
     * @param int $id The unique ID of the event location to be retrieved
     *
     * @return array The event location as array or NULL, if matching location was not found
     */
    public function findById(int $id) : ?array
    {
        return $this->querySingle($id, ['expand' => '~all']);
    }


    /**
     * Returns a collection of event locations, matched by a given search phrase.
     *
     * @param string $searchPhrase The search phrase
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of event locations
     */
    public function findBySearchPhrase(string $searchPhrase, array $params = []) : Collection
    {
        return $this->queryCollection([
            'search' => $searchPhrase
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'event_locations';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'name';
    }
}
