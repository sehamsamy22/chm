<?php
namespace App\Modules\Subscribe\Repositories;
use App\Modules\Subscribe\Entities\Subscribe;

class SubscribeRepository
{
    protected $subscribe;
    // Constructor to bind model to repo
    public function __construct(Subscribe $subscribe)
    {
        $this->subscribe= $subscribe;
    }
    // Get all instances of model
    public function all()
    {
        return $this->subscribe->all();
    }
    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscribe->create($data);
    }


    // remove record from the database
    public function delete($id): int
    {
        return $this->subscribe->destroy($id);
    }
    // Get the associated model
    public function getModel(): Subscribe
    {
        return $this->subscribe;
    }
}
