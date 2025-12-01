<?php
namespace Utils\Patterns\Observer;

class ExpirationObserver implements Observer {

    public function update($eventData) {

        if ($eventData['type'] === 'expiration') {

            $label = $eventData['label'];
            $expiryDate = strtotime($eventData['date']);
            $daysLeft = round(($expiryDate - time()) / (60 * 60 * 24));

            if ($daysLeft <= 30 && $daysLeft > 0) {
                echo "<p style='color:orange;'>⚠️ Warning: $label expires in $daysLeft days.</p>";
            }

            if ($daysLeft <= 0) {
                echo "<p style='color:red;'>❌ $label is expired!</p>";
            }
        }
    }
}
