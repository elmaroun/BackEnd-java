<?php
namespace App\Patterns\Strategy\Interfaces;

interface FilterStrategy
{
    public function apply($query);
}