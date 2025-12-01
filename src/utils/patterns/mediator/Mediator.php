<?php
namespace Utils\Patterns\Mediator;

class Mediator
{
    private $components = [];

    // Register component with a name (key)
    public function register(string $name, $component)
    {
        $this->components[$name] = $component;
    }

    // Run validation through all registered components
    public function validate(array $formData): array
    {
        $errors = [];

        foreach ($this->components as $name => $component) {
            if (isset($formData[$name])) {
                $result = $component->validate($formData[$name]);

                if ($result !== null) {
                    $errors[] = $result;
                }
            }
        }

        return $errors;
    }
}
