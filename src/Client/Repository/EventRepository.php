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


     /**
     * Filters retrieved event items to not include unwanted fields.
     *
     * @param array|null $event The event item to filter
     *
     * @return array|null The filtered event item or null if it was not found
     */
    protected function filterItem(array | null $event): array | null
    {
        $event = parent::filterItem($event);

        if ($event === null) {
            return null;
        }

        if (array_key_exists('files', $event) && is_array($event['files'])) {
            $this->removeProtectedDocuments($event['files']);
        }

        return $event;
    }


    protected function removeProtectedDocuments(array &$documents): void
    {
        foreach ($documents as $key => $document) {
            if (!array_key_exists('visible_for_all', $document) || !$document['visible_for_all']) {
                unset($documents[$key]);
            }
        }

        // Re-index the array to avoid gaps in the keys
        $documents = array_values($documents);
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
