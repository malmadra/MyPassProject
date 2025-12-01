<?php
namespace Utils\Patterns\Chain;

require_once __DIR__ . '/BaseHandler.php';

class LengthHandler extends BaseHandler
{
    public function validate(string $password): ?string
    {
        if (strlen($password) < 8) {
            return "Chain: Password must be at least 8 characters.";
        }
        return parent::validate($password);
    }
}
