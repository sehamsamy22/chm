<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\SubscriptionType;
use App\Modules\Subscription\Repositories;

class SubscriptionTypeRepository implements WrappingTypeRepositoryInterface
{
    // model property on class instances
    protected $subscriptionType;

    // Constructor to bind model to repo
    public function __construct(SubscriptionType $subscriptionType)
    {
        $this->subscriptionType = $subscriptionType;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscriptionType->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscriptionType->create($data);
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
        return $this->subscriptionType->destroy($id);
    }
    // show the record with the given id
    public function show($id)
    {
        return $this->subscriptionType->findOrFail($id);
    }
    // Get the associated model
    public function getModel()
    {
        return $this->subscriptionType;
    }
    // Set the associated model
    public function setModel($model)
    {
        $this->subscriptionType = $model;
        return $this;
    }
    // Eager load database relationships
    public function with($relations)
    {
        return $this->subscriptionType->with($relations);
    }
    // more

}
