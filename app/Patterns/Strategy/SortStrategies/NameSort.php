<?php
namespace App\Patterns\Strategy\sortStrategies;
use  App\Patterns\Strategy\Interfaces\FilterStrategy;


class NameSort implements FilterStrategy
{
    public function apply($query)
    {
        return $query->join('clients', 'demandes.client_id', '=', 'clients.id')
                    ->orderBy('clients.nom', 'desc');
    }
}