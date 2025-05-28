<?php

namespace App\Repositories;

use App\Models\Avis;

interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByUser($userId);
    public function getLatest($limit = 5);
    // Add any other custom methods you need
}