<?php
namespace Utils\Patterns\Observer;

interface Observer {
    public function update($itemData);
}
