<?php

namespace App\Imports;

use App\Models\User;
use App\Modules\Category\Entities\Category;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Entities\ProductOptionValue;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $totalCount = 0;
        $totalMissedCount = 0;
        $totalCreatedCount = 0;
        $totalUpdatedCount = 0;
        $missedDetails = [];
        $productTags = isset($row['tags']) && !empty($row['tags']) ? explode(',', $row['tags']) : '';
        $productImages = isset($row['images']) && !empty($row['images']) ? explode(',', $row['images']) : '';

        //SKU check if exist
//        TODO check if sku is unique
        if (isset($row['sku']) && $row['sku'] != null) {
            if (isset($row['category']) && $row['category'] != null && isset($row['main_category']) && $row['main_category'] != null) {
                $query = Category::query();
                $query->where('name->en', $row['category']);
                $query->whereHas('mainCategory', function ($q) use ($row) {
                    $q->where('name->en', $row['main_category']);
                });
                $subCategory = $query->first();
                if ($subCategory) {
                    $subCategoryID = $subCategory->id;
                }
            }
            $row['sku'] = 3213;
            $product = Product::where('SKU', $row['sku'])->first();
            if (!$product) {
                $product = Product::create([
                    'id' => $row['id'] ?? '',
                    'SKU' => $row['sku'] ?? '',
                    'name' => [
                        'en' => $row['ar_name'] ?? '',
                        'ar' => $row['en_name'] ?? ''
                    ],
                    'description' => [
                        'en' => $row['en_description'] ?? '',
                        'ar' => $row['ar_description'] ?? ''
                    ],
                    'category_id' => $subCategoryID,
                    'creator_id' => Auth::id(),
                    'price' => $row['price'] ?? '',
                    'discount_price' => $row['discount_price'] ?? '',
                    'discount_start_date' => $row['discount_start_date'] ?? '',
                    'discount_end_date' => $row['discount_end_date'] ?? '',
                    'image' => $row['image'] ?? '',
                    'stock' => $row['stock'] ?? '',
                    'deactivated_at' => $row['deactivated_at'] ?? '',
                    'deactivation_notes' => $row['deactivation_notes'] ?? '',
                    'max_per_order' => $row['max_per_order'] ?? '',
                    'digit' => $row['digit'] ?? '',
                    'bundle' => $row['bundle'] ?? '',
                ]);
                // attach  images to product
                if (is_array($productImages) && count($productImages)) {
                    $product->images()->delete();
                    foreach ($productImages as $image) {
                        $product->images()->create(["image" => $image]) ?? '';
                    }
                }
                // attach  tags to product
                if (is_array($productTags) && count($productTags)) {
                    foreach ($productTags as $tag) {
                        $product->tags()->create(["tag" => $tag]) ?? '';
                    }
                }
                //option check
                $keys = array_keys($row);
                $options = [];
                $matchingKeys = preg_grep("/option_/", $keys);
                foreach ($matchingKeys as $key) {
                    $option = substr($key, 7);
                    array_push($options, $option);
                }
                $mainCategory = Category::where('name->en', $row['main_category'])->first();
                $categoryOption = [];
                foreach ($mainCategory->options as $option) {
                    $optionName = $option->getTranslations('name')['en'];
                    if (in_array($optionName, $options)) {
                        //    array_push($categoryOption, $option->getTranslations('name')['en']);
                        if (isset($row['option_'.$optionName])) {
                            ProductOptionValue::create([
                                'product_id' => $product->id,
                                'option_id' => $option->id,
                                'value' => $row['option_' . $optionName]
                            ]);
                        }
                    }
                }
                //
                $totalCreatedCount++;
            } else {
                $totalMissedCount++;
                $missedDetails[] = 'Product with sku ' . $row['sku'] . 'is already Exist';
            }

        }

    }

//    public function StartRow(): int
//    {
//        return 2;
//    }

}
