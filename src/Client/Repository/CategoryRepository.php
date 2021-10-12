<?php
namespace Priorist\EDM\Client\Repository;

use Priorist\EDM\Client\Collection;


class CategoryRepository extends AbstractSearchableRepository
{
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
        return $this->fetchCollection([
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
        return $this->fetchCollection([
            'parent_category__isnull' => 1,
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
