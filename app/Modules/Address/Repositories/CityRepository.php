<?php namespace App\Modules\Address\Repositories;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Entities\City;

class CityRepository
{
    // model property on class instances
    protected $city;
    // Constructor to bind model to repo
    public function __construct(City $city)
    {
        $this->city = $city;
    }
    // Get all instances of model
    public function all()
    {
        return $this->city->all();
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->city->destroy($id);
    }

}
