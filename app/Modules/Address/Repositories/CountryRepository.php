<?php namespace App\Modules\Address\Repositories;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Entities\City;
use App\Modules\Address\Entities\Country;

class CountryRepository
{
    // model property on class instances
    protected $country;
    // Constructor to bind model to repo
    public function __construct(Country $country)
    {
        $this->country = $country;
    }
    // Get all instances of model
    public function all()
    {
        return $this->country->all();
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->country->destroy($id);
    }

}
