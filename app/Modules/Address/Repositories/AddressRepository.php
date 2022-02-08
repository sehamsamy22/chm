<?php namespace App\Modules\Address\Repositories;
use App\Modules\Address\Entities\Address;
class AddressRepository
{
    // model property on class instances
    protected $Address;
    // Constructor to bind model to repo
    public function __construct(Address $Address)
    {
        $this->Address = $Address;
    }
    // Get all instances of model
    public function all()
    {
        return $this->Address->all();
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->Address->destroy($id);
    }

}
