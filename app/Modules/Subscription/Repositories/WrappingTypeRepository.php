<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\WrappingType;
use App\Modules\Subscription\Repositories;

class WrappingTypeRepository implements WrappingTypeRepositoryInterface
{
    // model property on class instances
    protected $wrappingType;

    // Constructor to bind model to repo
    public function __construct(WrappingType $wrappingType)
    {
        $this->wrappingType = $wrappingType;
    }

    // Get all instances of model
    public function all()
    {
        return $this->wrappingType->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->wrappingType->create($data);
    }
    // update record in the database
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }
    // remove record from the database
    public function delete($id)
    {
        return $this->wrappingType->destroy($id);
    }
    // show the record with the given id
    public function show($id)
    {
        return $this->wrappingType->findOrFail($id);
    }
    // Get the associated model
    public function getModel()
    {
        return $this->wrappingType;
    }
    // Set the associated model
    public function setModel($model)
    {
        $this->wrappingType = $model;
        return $this;
    }
    // Eager load database relationships
    public function with($relations)
    {
        return $this->wrappingType->with($relations);
    }
    // more

}
