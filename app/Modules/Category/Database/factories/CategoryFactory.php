<?php

namespace App\Modules\Category\Database\factories;

use App\Modules\Category\Entities\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = array(
            'ar' => ['موبيلات', 'ملابس', 'اجهزه', 'مواد بناء', 'ادوات تجميل', 'احذيه', 'ماركيت', 'لابتوبات']
        );

        return [
            'name' =>['en'=>$this->faker->name,'ar'=> $category['ar'][array_rand($category['ar'])]] ,
            'image' => '/storage/uploads/w2jfxC-1626008205.jpg',
            'parent_id' => 1,
        ];
    }
}

