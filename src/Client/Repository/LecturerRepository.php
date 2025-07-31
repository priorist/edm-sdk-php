<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class LecturerRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath(): string
    {
        return 'lecturers';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'last_name';
    }
}
