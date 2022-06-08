<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Repositories;

class SubscriptionSizeRepository implements WrappingTypeRepositoryInterface
{
    // model property on class instances
    protected $subscriptionSize;

    // Constructor to bind model to repo
    public function __construct(SubscriptionSize $subscriptionSize)
    {
        $this->subscriptionSize = $subscriptionSize;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscriptionSize->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscriptionSize->create($data);
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
        return $this->subscriptionSize->destroy($id);
    }
    // show the record with the given id
    public function show($id)
    {
        return $this->subscriptionSize->findOrFail($id);
    }
    // Get the associated model
    public function getModel()
    {
        return $this->subscriptionSize;
    }
    // Set the associated model
    public function setModel($model)
    {
        $this->subscriptionSize = $model;
        return $this;
    }
    // Eager load database relationships
    public function with($relations)
    {
        return $this->subscriptionSize->with($relations);
    }
    // more

}
