<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class TagRepository extends AbstractRepository
{
    /**
     * Returns a single tag.
     *
     * @param int $id The unique ID of the tag
     *
     * @return array The tag as array or NULL, if matching category was not found
     */
    public function findById(int $id) : ?array
    {
        return $this->querySingle($id, ['expand' => '~all']);
    }


    /**
     * Returns a collection of tags, matched by a given search phrase.
     *
     * @param string $searchPhrase The search phrase
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of tags
     */
    public function findBySearchPhrase(string $searchPhrase, array $params = []) : Collection
    {
        return $this->queryCollection([
            'search' => $searchPhrase,
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'tags';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'name';
    }
}
