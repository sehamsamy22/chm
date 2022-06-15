<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\Subscription;
use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Repositories;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    // model property on class instances
    protected $subscription;

    // Constructor to bind model to repo
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscription->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscription->create($data);
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
        return $this->subscription->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->subscription->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->subscription;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->subscription = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->subscription->with($relations);
    }
    // more

}
