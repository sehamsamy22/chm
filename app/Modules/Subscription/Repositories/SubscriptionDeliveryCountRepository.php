<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\SubscriptionDeliveryCount;
use App\Modules\Subscription\Repositories;

class SubscriptionDeliveryCountRepository implements SubscriptionDeliveryRepositoryInterface
{
    // model property on class instances
    protected $subscriptionDeliveryCount;

    // Constructor to bind model to repo
    public function __construct(SubscriptionDeliveryCount $subscriptionDeliveryCount)
    {
        $this->subscriptionDeliveryCount = $subscriptionDeliveryCount;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscriptionDeliveryCount->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscriptionDeliveryCount->create($data);
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
        return $this->subscriptionDeliveryCount->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->subscriptionDeliveryCount->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->subscriptionDeliveryCount;
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
        return $this->subscriptionDeliveryCount->with($relations);
    }
    // more

}
