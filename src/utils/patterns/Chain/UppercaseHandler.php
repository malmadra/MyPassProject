<?php
namespace Utils\Patterns\Chain;

require_once __DIR__ . '/BaseHandler.php';

class UppercaseHandler extends BaseHandler
{
    public function validate(string $password): ?string
    {
        if (!preg_match('/[A-Z]/', $password)) {
            return "Chain: Password must contain at least one uppercase letter.";
        }
        return parent::validate($password);
    }
}
