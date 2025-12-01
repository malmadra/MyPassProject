<?php
namespace Utils\Patterns\Observer;

class WeakPasswordObserver implements Observer {

    public function update($eventData) {
        if ($eventData['type'] === 'password') {
            $password = $eventData['value'];

            if (strlen($password) < 8) {
                echo "<p style='color:red;'⚠️ Weak Password: Must be at least 8 characters.</p>";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                echo "<p style='color:red;'>⚠️ Weak Password: Add uppercase letters.</p>";
            }

            if (!preg_match('/[0-9]/', $password)) {
                echo "<p style='color:red;'>⚠️ Weak Password: Add numbers.</p>";
            }
        }
    }
}
