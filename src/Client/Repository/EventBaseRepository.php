<?php
namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Collection;


class EventBaseRepository extends AbstractSearchableRepository
{
    /**
     * Returns a single event base having child relations expanded.
     *
     * @param string $slug The unique slug of the event base
     *
     * @return array The event base as array or NULL, if matching event base was not found
     */
    public function findBySlug(string $slug, array $params = []) : ?array
    {
        return $this->findById($slug, $params);
    }


    /**
     * Returns a single event base having child relations expanded.
     *
     * @param int $id The unique ID of the event base
     *
     * @return array The event base as array or NULL, if matching event base was not found
     */
    public function findById($id, array $params = []) : ?array
    {
        return parent::fetchSingle($id, ['expand' => '~all,events.location,events.lecturers'], $params);
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
