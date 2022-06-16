<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\CareInstruction;

class CareInstructionRepository implements CareInstructionRepositoryInterface
{
    // model property on class instances
    protected $careInstruction;

    // Constructor to bind model to repo
    public function __construct(CareInstruction $careInstruction)
    {
        $this->careInstruction = $careInstruction;
    }

    // Get all instances of model
    public function all()
    {
        return $this->careInstruction->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->careInstruction->create($data);
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
        return $this->careInstruction->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->careInstruction->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->careInstruction;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->careInstruction = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->careInstruction->with($relations);
    }
    // more

}
