<?php
namespace Priorist\EDM\Client;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;


interface User extends ResourceOwnerInterface
{
    public function getName() : string;
    public function hasRole(string $role) : bool;
    public function getRoles() : array;
    public function get(string $name, $default = null);
}
