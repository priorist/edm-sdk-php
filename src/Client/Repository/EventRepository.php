<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventRepository extends AbstractRepository
{
    /**
     * Returns a single event.
     *
     * @param int $id The unique ID of the event to be retrieved
     *
     * @return array The event as are or NULL, if matching event was not found
     */
    public function findById(int $id) : ?array
    {
        return $this->querySingle($id, ['expand' => '~all']);
    }


    /**
     * Returns a collection of upcoming events belonging to certain categories.
     *
     * @param int $categoryId ID of the category
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of events
     */
    public function findUpcomingByCategory(int $categoryId, array $params = []) : Collection
    {
        return $this->findUpcomingByCategories([$categoryId], $params);
    }


    /**
     * Returns a collection of upcoming events belonging to certain categories.
     *
     * @param array $categoryIds Array containing IDs of the categories
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of events
     */
    public function findUpcomingByCategories(array $categoryIds, array $params = []) : Collection
    {
        return $this->findUpcoming(array_merge([
            'event_base__categories' => $categoryIds
        ], $params));
    }


    /**
     * Returns a collection of all upcoming events including today, orderer by day.
     *
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of events
     */
    public function findUpcoming(array $params = []) : Collection
    {
        return $this->queryCollection([
            'first_day__gte' => date('Y-m-d'),
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'events';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'first_day';
    }
}
