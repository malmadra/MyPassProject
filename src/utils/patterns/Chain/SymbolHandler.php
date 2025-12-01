<?php
namespace Utils\Patterns\Chain;

require_once __DIR__ . '/BaseHandler.php';

class SymbolHandler extends BaseHandler
{
    public function validate(string $password): ?string
    {
        if (!preg_match('/[\W]/', $password)) {  // non-alphanumeric
            return "Chain: Password must contain at least one special symbol.";
        }
        return parent::validate($password);
    }
}
