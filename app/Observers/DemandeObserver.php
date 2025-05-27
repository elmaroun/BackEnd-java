<?php
namespace App\Observers;

interface DemandeObserver
{
    public function notifyStatusChange(string $newStatus);
}