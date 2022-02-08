<?php namespace App\Modules\Warehouse\Repositories;
use App\Modules\Warehouse\Entities\Warehouse;

class WarehouseRepository
{
    // model property on class instances
    protected $warhouse;
    // Constructor to bind model to repo
    public function __construct(Warehouse $warhouse)
    {
        $this->Warehouse = $warhouse;
    }
    // Get all instances of model
    public function all()
    {
        return $this->Warehouse->all();
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->Warehouse->destroy($id);
    }

}
