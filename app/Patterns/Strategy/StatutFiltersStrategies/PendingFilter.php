<?php
namespace App\Patterns\Strategy\StatutFiltersStrategies;
use  App\Patterns\Strategy\Interfaces\FilterStrategy;


class PendingFilter implements FilterStrategy
{
    public function apply($query)
    {
        return $query->where('statut', 'En Attente');
    }
}
