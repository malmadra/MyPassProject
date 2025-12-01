<?php
/**
 * Builder Pattern for Password Generation
 * ---------------------------------------
 * This builder allows you to generate a password with:
 * - custom length
 * - uppercase letters
 * - lowercase letters
 * - numbers
 * - special symbols
 */

interface PasswordBuilderInterface {
    public function setLength(int $length);
    public function useUppercase(bool $enabled);
    public function useLowercase(bool $enabled);
    public function useNumbers(bool $enabled);
    public function useSymbols(bool $enabled);
    public function build(): string;
}

class PasswordBuilder implements PasswordBuilderInterface {

    private $length = 12;
    private $uppercase = true;
    private $lowercase = true;
    private $numbers = true;
    private $symbols = true;

    public function setLength(int $length) {
        $this->length = max(4, $length); // minimum strong length
        return $this;
    }

    public function useUppercase(bool $enabled) {
        $this->uppercase = $enabled;
        return $this;
    }

    public function useLowercase(bool $enabled) {
        $this->lowercase = $enabled;
        return $this;
    }

    public function useNumbers(bool $enabled) {
        $this->numbers = $enabled;
        return $this;
    }

    public function useSymbols(bool $enabled) {
        $this->symbols = $enabled;
        return $this;
    }

    public function build(): string {
        $characters = "";

        if ($this->uppercase) $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($this->lowercase) $characters .= "abcdefghijklmnopqrstuvwxyz";
        if ($this->numbers)   $characters .= "0123456789";
        if ($this->symbols)   $characters .= "!@#$%^&*()-_=+<>?";

        if (empty($characters)) {
            throw new Exception("No character types selected.");
        }

        $password = "";
        for ($i = 0; $i < $this->length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
?>
