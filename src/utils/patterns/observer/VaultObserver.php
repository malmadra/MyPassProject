<?php
namespace Utils\Patterns\Observer;

class VaultObserver implements Observer {
    public function update($itemData) {
        // For demonstration, we just log to a file
        $log = "Vault item added: " . json_encode($itemData) . "\n";
        file_put_contents(__DIR__ . '/vault_log.txt', $log, FILE_APPEND);
    }
}
