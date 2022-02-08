<?php namespace App\Modules\Category\Repositories;

use App\Modules\Category\Entities\Option;

class  OptionRepository
{
    // model property on class instances
    protected $option;

    // Constructor to bind model to repo
    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    // Get all instances of model
    public function all()
    {
        return $this->option->all();
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->option->destroy($id);
    }

}
