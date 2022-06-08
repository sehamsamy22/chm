<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Entities\SubscriptionItem;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Repositories;

class SubscriptionItemRepository implements SubscriptionItemRepositoryInterface
{
    // model property on class instances
    protected $subscriptionItem;

    // Constructor to bind model to repo
    public function __construct(SubscriptionItem $subscriptionItem)
    {
        $this->subscriptionItem = $subscriptionItem;
    }

    // Get all instances of model
    public function all()
    {
        return $this->subscriptionItem->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->subscriptionItem->create($data);
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
        return $this->subscriptionItem->destroy($id);
    }
    // show the record with the given id
    public function show($id)
    {
        return $this->subscriptionItem->findOrFail($id);
    }
    // Get the associated model
    public function getModel()
    {
        return $this->subscriptionItem;
    }
    // Set the associated model
    public function setModel($model)
    {
        $this->subscriptionItem = $model;
        return $this;
    }
    // Eager load database relationships
    public function with($relations)
    {
        return $this->subscriptionItem->with($relations);
    }
    // more

}
