<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->on('admins')->references('id')->onDelete('cascade');
            $table->text('name');
            $table->longText('description');
            $table->string('SKU', 50)->unique()->nullable();
            $table->double('price', 8,2);
            $table->double('discount_price', 8,2)->nullable();
            $table->datetime('discount_start_date');
            $table->datetime('discount_end_date');
            $table->string('image');
            $table->integer('stock');
            $table->timestamp('deactivated_at')->nullable();
            $table->text('deactivation_notes')->nullable();
            $table->integer('max_per_order')->nullable();
            $table->boolean('digit');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
