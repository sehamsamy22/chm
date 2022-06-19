<?php
namespace App\Modules\Subscription\Repositories;

interface NormalSupscriptionRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id);
}