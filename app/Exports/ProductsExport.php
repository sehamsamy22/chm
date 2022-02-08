<?php

namespace App\Exports;

use App\Modules\Category\Entities\Option;
use App\Modules\Product\Entities\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{

    private $products;
    private $productOptionIds;
    private $productOptionCount;

    public function __construct()
    {
        $optionsCount = 0;
        $productOptionCount = 0;
        $productOptionIds = [];
        $products = Product::query();
        $productOptionCount = Product::withCount('options')->OrderByDesc('options_count')->first()->option_count;
        $productOptionIds = Product::withCount('options')->OrderByDesc('options_count')->first()->options->pluck('id');
        $this->productOptionIds = $productOptionIds;
        $this->products = $products;
    }


    /**
     * @return array
     */
    public function collection()
    {
        $products = Product::select(['id', 'name', 'description', 'SKU', 'price', 'discount_price', 'discount_start_date', 'discount_end_date', 'image', 'stock', 'deactivated_at', 'deactivation_notes', 'max_per_order'
            , 'digit', 'category_id', 'creator_id', 'bundle', 'created_at'])->get();
        $optionvalues = [];;
        return $products->map(function ($product) {
            $tags = $product->tags->pluck('tag')->implode(',');
            $images = $product->images->pluck('image')->implode(',');
            $productOptionValue = $product->options->pluck('pivot.value')->toArray();

            $mainProductsArray =
                [
                    $product->id,
                    $product->getTranslations('name')['ar'] ?? '',
                    $product->getTranslations('name')['en'] ?? '',
                    isset($product->getTranslations('description')['ar']) ? strip_tags($product->getTranslations('description')['ar']): '',
                    isset($product->getTranslations('description')['en']) ? strip_tags($product->getTranslations('description')['en']): '',
                    $product->SKU,
                    $product->price,
                    $product->discount_price,
                    $product->discount_start_date,
                    $product->discount_end_date,
                    $product->image,
                    $product->stock,
                    $product->deactivated_at,
                    $product->deactivation_notes,
                    $product->max_per_order,
                    $product->digit,
                    $product->category->getTranslations('name')['ar']??'' ,
                   optional(optional($product->category)->mainCategory)->getTranslations('name')['ar'] ??'',
                    $product->bundle,
                    $product->creator->name,
                    $tags,
                    $images,
                    $product->created_at->format('Y-m-d H:i:s'),
                ];
            foreach ($productOptionValue as $value) {
                $value != Null ? $value : $value = '';
                array_push($mainProductsArray, $value);
            }
            return $mainProductsArray;
        });
    }


    public function headings(): array
    {
        $optionsAr = [];
        $optionsEn = [];
        $headingArray = ['id', 'ar_name', 'en_name', 'ar_description', 'en_description', 'SKU', 'price', 'discount_price', 'discount_start_date', 'discount_end_date', 'image', 'stock', 'deactivated_at', 'deactivation_notes', 'max_per_order'
            , 'digit', 'category', 'main_category', 'bundle', 'creator', 'tags', 'images', 'created_at'];
        $options = Option::whereIn('id', $this->productOptionIds)->get();
        foreach ($options as $option) {
            array_push($optionsAr, $option->getTranslations('name')['ar']);
            array_push($optionsEn, "option_" . $option->getTranslations('name')['en']);
        }
        $fullHeadingArray = array_merge($headingArray, $optionsEn);
        return $fullHeadingArray;
    }
}
