<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Collection;

class EventRepository extends AbstractSearchableRepository
{
    /**
     * Returns a single item of the repository.
     *
     * @param int $id The unique ID of the item
     *
     * @return array The item as array or NULL, if matching item was not found
     */
    public function findById($id, array $params = []): array | null
    {
        return $this->fetchSingle($id, [
            'expand' => '~all',
            'is_public' => 'true'
        ], $params);
    }


    /**
     * Returns a collection of upcoming events belonging to certain categories.
     *
     * @param int $categoryId ID of the category
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of events
     */
    public function findUpcomingByCategory(int $categoryId, array $params = []): Collection
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
    public function findUpcomingByCategories(array $categoryIds, array $params = []): Collection
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
    public function findUpcoming(array $params = []): Collection
    {
        return $this->fetchCollection([
            'first_day__gte' => date('Y-m-d'),
            'status' => ['OFFERED', 'TAKES_PLACE'],
            'is_public' => 'true'
        ], $params);
    }


    public static function getEndpointPath(): string
    {
        return 'events';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'first_day';
    }
}
