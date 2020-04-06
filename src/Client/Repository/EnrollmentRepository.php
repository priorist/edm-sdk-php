<?php
namespace Priorist\AIS\Client\Repository;


class EnrollmentRepository extends AbstractSearchableRepository
{
    public static function getEndpointPath() : string
    {
        return 'enrollments';
    }


    protected static function getDefaultOrdering() : string
    {
        return 'last_name';
    }
}
