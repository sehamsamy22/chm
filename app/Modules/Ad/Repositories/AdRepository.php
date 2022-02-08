<?php namespace App\Modules\Ad\Repositories;

use App\Modules\Ad\Entities\Ad;
use App\Modules\Category\Entities\Category;
use App\Modules\Product\Entities\Lists;
use App\Modules\Product\Entities\Product;

class AdRepository
{
    // model property on class instances
    protected $ad;

    // Constructor to bind model to repo
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    public function customType($data)
    {
        if (isset($data['model_type'])) {
            switch ($data['model_type']) {
                case 'product' :
                    $data['model_type'] = Product::class;
                    break;
                case 'category' :
                    $data['model_type'] = Category::class;
                    break;
                case 'Lists' :
                    $data['model_type'] = Lists::class;
                    break;
            }
        }
        return $data;
    }

    // Get all instances of model
    public function all()
    {
        return $this->ad->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        $customData = $this->customType($data);
        return $this->ad->create($customData);
    }

    // update record in the database
    public function update(array $data, $id)
    {
        $customData = $this->customType($data);
        $ad = $this->ad->find($id);
        $ad->update($customData);
        return $ad;
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->ad->destroy($id);
    }

    // Filter products
    public function filterProducts($params): object
    {
        $products = Product::query();
        if (isset($params['search_word'])) {
            $products->where(function ($q) use ($params) {
                $q->where('name', 'like', "%{$params['search_word']}%");
                $q->orWhere('description', 'like', "%{$params['search_word']}%");
                $q->orWhere('SKU', 'like', "%{$params['search_word']}%");
            });
        }
        return $products->get();
    }

    // Filter categories
    public function filterCategories($params): object
    {
        $categories = Category::query();
        if (isset($params['search_word'])) {
            $categories->where(function ($q) use ($params) {
                $q->where('name', 'like', "%{$params['search_word']}%");
            });
        }
        return $categories->get();
    }

    // Filter lists
    public function filterLists($params): object
    {
        $lists = Lists::query();
        if (isset($params['search_word'])) {
            $lists->where(function ($q) use ($params) {
                $q->where('name', 'like', "%{$params['search_word']}%");
                $q->orWhere('description', 'like', "%{$params['search_word']}%");
            });
        }
        return $lists->get();
    }
}
