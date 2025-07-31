<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class EnrollmentRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath(): string
    {
        return 'enrollments';
    }


    protected static function getDefaultOrdering(): string
    {
        return 'last_name'; // @codeCoverageIgnore
    }
}
