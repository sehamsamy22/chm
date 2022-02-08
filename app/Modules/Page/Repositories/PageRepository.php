<?php
namespace App\Modules\Page\Repositories;

use App\Modules\Page\Entities\Page;

class PageRepository implements PageRepositoryInterface
{
    // model property on class instances
    protected $page;

    // Constructor to bind model to repo
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    // Get all instances of model
    public function all()
    {
        return $this->page->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->page->create($data);
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
        return $this->page->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->page->findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->page;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->page = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->page->with($relations);
    }

    // more
}
