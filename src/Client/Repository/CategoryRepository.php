<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class CategoryRepository extends AbstractRepository
{
    /**
     * Returns a single category.
     *
     * @param int $id The unique ID of the category
     *
     * @return array The category as array or NULL, if matching category was not found
     */
    public function findById(int $id) : ?array
    {
        return $this->querySingle($id, ['expand' => '~all']);
    }


    /**
     * Returns a collection of all child categories of a given parent ID
     *
     * @param int $parentId ID of the parent category
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of categories
     */
    public function findByParent(int $parentId, array $params = [])
    {
        return $this->queryCollection([
            'parent_category' => $parentId,
        ], $params);
    }


    /**
     * Returns a collection of all categories on the top level without any parents.
     *
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of categories
     */
    public function findTopLevel(array $params = [])
    {
        return $this->queryCollection([
            'parent_category__isnull' => 1,
        ], $params);
    }


    /**
     * Returns a collection of categories, matched by a given search phrase.
     *
     * @param string $searchPhrase The search phrase
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of categories
     */
    public function findBySearchPhrase(string $searchPhrase, array $params = []) : Collection
    {
        return $this->queryCollection([
            'search' => $searchPhrase,
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'categories';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'name';
    }
}
