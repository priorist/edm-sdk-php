<?php

declare(strict_types=1);

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
    public function findBySlug(string $slug, array $params = []): array | null
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
    public function findById($id, array $params = []): array | null
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
    public function findAllWithEvents(array $params = []): Collection
    {
        return $this->fetchCollection([
            'expand' => 'events',
        ], $params);
    }


    /**
     * Filters retrieved event base items to not include unwanted fields.
     *
     * @param array|null $eventBase The event base item to filter
     *
     * @return array|null The filtered event base item or null if it was not found
     */
    protected function filterItem(array | null $eventBase): array | null
    {
        $eventBase = parent::filterItem($eventBase);

        if ($eventBase === null) {
            return null;
        }

        if (array_key_exists('documents', $eventBase) && is_array($eventBase['documents'])) {
            $this->removeProtectedDocuments($eventBase['documents']);
        }

        if (array_key_exists('files', $eventBase) && is_array($eventBase['files'])) {
            $this->removeProtectedDocuments($eventBase['files']);
        }

        if (array_key_exists('events', $eventBase) && is_array($eventBase['events'])) {
            foreach ($eventBase['events'] as $key => &$event) {
                // Ignore non-expanded events
                if (!is_array($event)) {
                    continue;
                }

                // Remove events that are not public or do not have a valid status
                if (!in_array($event['status'], ['TAKES_PLACE', 'OFFERED'])) {
                    unset($eventBase['events'][$key]);
                    continue;
                }

                // Filter files in events
                if (array_key_exists('files', $event) && is_array($event['files'])) {
                    $this->removeProtectedDocuments($event['files']);
                }
            }
        }

        return $eventBase;
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
        return 'event_bases';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'name';
    }
}
