<?php

declare(strict_types=1);

namespace Priorist\EDM\OAuth2\Client\Provider;

use Priorist\EDM\Client\User;

class EdmResourceOwner implements User
{
    protected int $id;
    protected array $data = [];


    public function __construct(array $data, $id)
    {
        $this->id = intval($id);
        $this->data = $data;
    }


    public function getName(): string
    {
        return trim(sprintf('%s %s %s', $this->get('title'), $this->get('first_name'), $this->get('last_name')));
    }


    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }


    public function getRoles(): array
    {
        return $this->get('roles', []);
    }


    public function get(string $name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function toArray(): array
    {
        return $this->data;
    }
}
