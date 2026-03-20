<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class UserRepository extends AbstractRepository
{
  public static function getEndpointPath(): string
  {
    return 'users';
  }

  protected static function getDefaultOrdering(): string
  {
    return 'id';
  }
}
