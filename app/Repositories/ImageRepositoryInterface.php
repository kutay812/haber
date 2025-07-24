<?php

namespace App\Repositories;

interface ImageRepositoryInterface
{
    public function find($id);
    public function findByName($name);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
