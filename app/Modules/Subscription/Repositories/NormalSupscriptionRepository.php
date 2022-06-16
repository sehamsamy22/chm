<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\CareInstruction;
use App\Modules\Subscription\Entities\NormalSubscription;

class NormalSupscriptionRepository implements NormalSupscriptionRepositoryInterface
{
    // model property on class instances
    protected $normalSupscription;

    // Constructor to bind model to repo
    public function __construct(NormalSubscription $normalSupscription)
    {
        $this->normalSupscription = $normalSupscription;
    }

    // Get all instances of model
    public function all()
    {
        return $this->normalSupscription->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->normalSupscription->create($data);
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
        return $this->normalSupscription->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->normalSupscription->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->normalSupscription;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->normalSupscription = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->normalSupscription->with($relations);
    }
    // more

}
