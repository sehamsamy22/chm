<?php

namespace App\Modules\Product\Database\factories;

use App\Modules\Category\Entities\Category;
use App\Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'SKU' => Str::random(5),
            'price' => rand(100, 1000),
            'image' => '/storage/uploads/w2jfxC-1626008205.jpg',
            'stock' => rand(10, 100),
            'category_id' => Category::factory(),
            'creator_id' => 1,
        ];
    }
}

