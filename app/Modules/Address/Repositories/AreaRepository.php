<?php namespace App\Modules\Address\Repositories;
use App\Modules\Address\Entities\Area;
class AreaRepository
{
    // model property on class instances
    protected $area;
    // Constructor to bind model to repo
    public function __construct(Area $area)
    {
        $this->area = $area;
    }
    // Get all instances of model
    public function all()
    {
        return $this->area->all();
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->area->destroy($id);
    }

}
