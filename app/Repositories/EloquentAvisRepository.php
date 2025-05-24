<?php

namespace App\Repositories;

use App\Repositories\AvisRepositoryInterface;
use App\Models\Avis;

class EloquentAvisRepository implements AvisRepositoryInterface
{
    protected $model;

    public function __construct(Avis $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function findByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getLatest($limit = 5)
    {
        return $this->model->latest()->limit($limit)->get();
    }
    public function getByProfessional($professionalId)
    {
        return $this->model->join('demandes', 'avis.demandes_id', '=', 'demandes.id')
            ->where('demandes.professionnal_id', $professionalId)
            ->join('clients', 'clients.id', '=', 'demandes.client_id')
            ->select('avis.*','clients.nom','clients.prenom','clients.img') 
            ->orderBy('avis.created_at', 'desc')
            ->get();
    }
    
    // Implement other custom methods here
}