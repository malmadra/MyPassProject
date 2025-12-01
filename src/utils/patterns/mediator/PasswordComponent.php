<?php
namespace Utils\Patterns\Mediator;

require_once __DIR__ . '/FormComponent.php';

class PasswordComponent extends FormComponent
{
    public function validate($value)
    {
        if (trim($value) === "") {
            return "Mediator: Password cannot be empty.";
        }

        if (strlen($value) < 6) {
            return "Mediator: Password must be at least 6 characters.";
        }

        return null;
    }
}
