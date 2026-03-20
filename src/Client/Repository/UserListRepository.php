<?php

declare(strict_types=1);

namespace Priorist\EDM\Client\Repository;

class UserListDataRepository extends AbstractRepository
{
  public function bulkCreate(array $data): array|null
  {
    return $this->getClient()->create('user_list_data/bulk_create', $data);
  }

  public static function getEndpointPath(): string
  {
    return 'user_list_data';
  }

  protected static function getDefaultOrdering(): string
  {
    return 'id';
  }
}
