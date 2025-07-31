<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class TagRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath(): string
    {
        return 'tags';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'name';
    }
}
