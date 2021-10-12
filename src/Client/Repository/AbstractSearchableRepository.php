<?php
namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Collection;


abstract class AbstractSearchableRepository extends AbstractRepository
{
    /**
     * Returns a single item of the repository.
     *
     * @param int $id The unique ID of the item
     *
     * @return array The item as array or NULL, if matching item was not found
     */
    public function findById($id, array $params = []) : ?array
    {
        return $this->fetchSingle($id, ['expand' => '~all'], $params);
    }


    /**
     * Returns a collection of all items in the collection
     *
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of items
     */
    public function findAll(array $params = []) : Collection
    {
        return $this->fetchCollection($params);
    }


    /**
     * Returns a collection of items, matched by a given search phrase.
     *
     * @param string $searchPhrase The search phrase
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of items
     */
    public function findBySearchPhrase(string $searchPhrase, array $params = []) : Collection
    {
        return $this->fetchCollection([
            'search' => $searchPhrase,
        ], $params);
    }
}
