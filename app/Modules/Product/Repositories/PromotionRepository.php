<?php namespace App\Modules\Product\Repositories;

use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\Promotion;

class PromotionRepository
{
    // Get all instances of model
    public function all()
    {
        return Promotion::all();
    }

    // store  new  instances of model
    public function store($data)
    {
        return Promotion::create($data);
    }


    // update  new  instances of model
    public function update($data, $promotion)
    {
        $promotion->update($data);
        return $promotion;
    }

    // remove record from the database
    public function delete($id)
    {
        return Promotion::destroy($id);
    }
}
