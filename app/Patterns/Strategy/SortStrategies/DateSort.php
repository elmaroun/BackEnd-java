<?php
namespace App\Patterns\Strategy\sortStrategies;
use  App\Patterns\Strategy\Interfaces\FilterStrategy;



class DateSort implements FilterStrategy
{
    public function apply($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}