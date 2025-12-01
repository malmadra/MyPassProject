<?php
namespace Utils\Patterns\Observer;

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify($itemData);
}
