<?php

namespace Utils\Patterns\Proxy;

require_once __DIR__ . '/VaultInterface.php';

class VaultAccessProxy implements VaultInterface
{
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function getItem($id)
    {
        if ($this->role !== "admin") {
            return "Access Denied: insufficient permissions.";
        }

        return "Access Granted: You are allowed to view vault item #$id";
    }
}
