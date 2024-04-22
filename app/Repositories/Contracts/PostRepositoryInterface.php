<?php

namespace App\Repositories\Contracts;

interface PostRepositoryInterface
{
    public function all();

    public function find($id);

    public function findByTag($tag);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
