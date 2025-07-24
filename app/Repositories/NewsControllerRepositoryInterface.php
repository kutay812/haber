<?php

namespace App\Repositories;

interface NewsControllerRepositoryInterface
{
    public function all();
    public function find($id);
    public function findBySlug($slug);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
