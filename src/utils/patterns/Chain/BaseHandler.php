<?php
namespace Utils\Patterns\Chain;

abstract class BaseHandler
{
    protected $next = null;

    public function setNext(BaseHandler $handler)
    {
        $this->next = $handler;
        return $handler; // allows chaining ->setNext()->setNext()
    }

    public function validate(string $password): ?string
    {
        if ($this->next) {
            return $this->next->validate($password);
        }
        return null;
    }
}
