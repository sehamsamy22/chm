<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('cart_items', 'cart_product');
        Schema::rename('category_options', 'category_option');
        Schema::rename('list_products', 'list_product');
        Schema::rename('order_products', 'order_product');
        Schema::rename('product_comments', 'product_comment');
        Schema::rename('product_rates', 'product_rate');
        Schema::rename('product_wishes', 'product_wish');
        Schema::rename('product_option_values', 'product_option_value');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
