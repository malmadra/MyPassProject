<?php
namespace Utils\Patterns\Mediator;

abstract class FormComponent
{
    abstract public function validate($value);
}
