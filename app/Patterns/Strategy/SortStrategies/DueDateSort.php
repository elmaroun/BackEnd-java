<?php
namespace App\Patterns\Strategy\sortStrategies;
use  App\Patterns\Strategy\Interfaces\FilterStrategy;


class DueDateSort implements FilterStrategy
{
    public function apply($query)
    {
        return $query->orderBy('date', 'desc');
    }
}