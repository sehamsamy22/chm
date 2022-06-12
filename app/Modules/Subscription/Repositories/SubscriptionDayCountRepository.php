<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\SubscriptionDayCount;
use App\Modules\Subscription\Repositories;

class SubscriptionDayCountRepository implements SubscriptionDayRepositoryInterface
{
    // model property on class instances
    protected $subscriptionDayCount;

    // Constructor to bind model to repo
    public function __construct(SubscriptionDayCount $subscriptionDayCount)
    {
        $this->subscriptionDayCount = $subscriptionDayCount;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscriptionDayCount->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscriptionDayCount->create($data);
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
        return $this->subscriptionDayCount->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->subscriptionDayCount->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->subscriptionDayCount;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->subscriptionDayCount = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->subscriptionDayCount->with($relations);
    }
    // more

}
