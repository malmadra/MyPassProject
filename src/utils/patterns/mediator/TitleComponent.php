<?php
namespace Utils\Patterns\Mediator;

require_once __DIR__ . '/FormComponent.php';

class TitleComponent extends FormComponent
{
    public function validate($value)
    {
        if (trim($value) === "") {
            return "Mediator: Title cannot be empty.";
        }
        return null;
    }
}
