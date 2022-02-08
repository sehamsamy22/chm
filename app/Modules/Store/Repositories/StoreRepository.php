<?php namespace App\Modules\Store\Repositories;

use App\Modules\Store\Entities\Store;

class StoreRepository
{
    // model property on class instances
    protected $store;

    // Constructor to bind model to repo
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    // Get all instances of model
    public function all()
    {
        return $this->store->all();
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->store->destroy($id);
    }

}
