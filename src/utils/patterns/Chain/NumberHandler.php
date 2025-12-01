<?php
namespace Utils\Patterns\Chain;

require_once __DIR__ . '/BaseHandler.php';

class NumberHandler extends BaseHandler
{
    public function validate(string $password): ?string
    {
        if (!preg_match('/[0-9]/', $password)) {
            return "Chain: Password must contain at least one number.";
        }
        return parent::validate($password);
    }
}
