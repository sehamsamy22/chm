<?php
namespace App\Modules\Blog\Repositories;

interface BlogRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id);
}
