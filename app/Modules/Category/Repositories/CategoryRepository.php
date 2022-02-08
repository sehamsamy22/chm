<?php namespace App\Modules\Category\Repositories;

use App\Modules\Category\Entities\Category;

class CategoryRepository
{
    // model property on class instances
    protected $category;

    // Constructor to bind model to repo
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    // Get all instances of model
    public function all()
    {
        return $this->category->whereNull('parent_id')->get();
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->category->destroy($id);
    }
    public function haveAdditions()
    {
        return $this->category->haveAdditions()->get();
    }
      public function notHaveAdditions()
    {
        return $this->category->notHaveAdditions()->get();
    }
}
