<?php
/**
 * PasswordDirector
 * ----------------
 * Controls how the password is built.
 * For example, it can produce:
 * - a standard strong password
 * - a very strong password
 * - a simple password
 */

require_once "PasswordBuilder.php";

class PasswordDirector {

    private $builder;

    public function __construct(PasswordBuilderInterface $builder) {
        $this->builder = $builder;
    }

    // Standard password: 12-char strong
    public function buildStandardPassword() : string {
        return $this->builder
            ->setLength(12)
            ->useUppercase(true)
            ->useLowercase(true)
            ->useNumbers(true)
            ->useSymbols(true)
            ->build();
    }

    // Extra-strong: 18 chars with all types
    public function buildStrongPassword() : string {
        return $this->builder
            ->setLength(18)
            ->useUppercase(true)
            ->useLowercase(true)
            ->useNumbers(true)
            ->useSymbols(true)
            ->build();
    }

    // Simple password (not recommended)
    public function buildSimplePassword() : string {
        return $this->builder
            ->setLength(10)
            ->useUppercase(false)
            ->useSymbols(false)
            ->useLowercase(true)
            ->useNumbers(true)
            ->build();
    }
}
?>
