<?php

namespace App\Modules\Blog\Repositories;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Repositories\WrappingTypeRepositoryInterface;

class WrappingTypeRepository implements WrappingTypeRepositoryInterface
{
    // model property on class instances
    protected $blog;

    // Constructor to bind model to repo
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    // Get all instances of model
    public function all()
    {
        return $this->blog->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->blog->create($data);
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
        return $this->blog->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->blog->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->blog;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->blog = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->blog->with($relations);
    }

    // more

}
