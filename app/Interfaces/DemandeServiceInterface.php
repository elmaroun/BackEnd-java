<?php

namespace App\Interfaces;

interface DemandeServiceInterface
{
    public function client();
    public function professional();
    public function adresse();
    public function description();
    public function dateSouhaite();
    public function statut();
    public function dateCreation();
    public function notifyClient();
}