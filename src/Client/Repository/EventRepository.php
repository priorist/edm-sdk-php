<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class EventRepository extends AbstractSearchableRepository
{
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
        return $this->fetchCollection([
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
