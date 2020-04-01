<?php
namespace Priorist\AIS\Client\Repository;

use Priorist\AIS\Client\Collection;


class LecturerRepository extends AbstractRepository
{
    /**
     * Returns a single lecturer.
     *
     * @param int $id The unique ID of the lecturers
     *
     * @return array The lecturers as array or NULL, if matching lecturers was not found
     */
    public function findById(int $id) : ?array
    {
        return $this->querySingle($id, ['expand' => '~all']);
    }


    /**
     * Returns a collection of lecturers, matched by a given search phrase.
     *
     * @param string $searchPhrase The search phrase
     * @param array $params Optional query parameters to filter the results
     *
     * @return Collection The collection of lecturers
     */
    public function findBySearchPhrase(string $searchPhrase, array $params = []) : Collection
    {
        return $this->queryCollection([
            'search' => $searchPhrase,
        ], $params);
    }


    public static function getEndpointPath() : string
    {
        return 'lecturers';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'last_name';
    }
}
