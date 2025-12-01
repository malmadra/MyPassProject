<?php
namespace Utils\Patterns\Observer;

class VaultSubject implements Subject {
    private $observers = [];

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, fn($obs) => $obs !== $observer);
    }

    public function notify($itemData) {
        foreach ($this->observers as $observer) {
            $observer->update($itemData);
        }
    }
}
