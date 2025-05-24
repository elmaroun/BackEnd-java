<?php
namespace App\Patterns\Strategy;

use App\Patterns\Strategy\Interfaces\FilterStrategy;

class FilterService
{
    private $strategy;

    public function setStrategy(FilterStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function apply($query)
    {
        return $this->strategy->apply($query);
    }
}