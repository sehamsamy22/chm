<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('method_id')->unsigned();
            $table->foreign('method_id')->on('shipping_methods')->references('id')->onDelete('cascade');
            $table->string('name');
            $table->text('value')->nullable();
            $table->text('default')->nullable();
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
        Schema::dropIfExists('shipping_credentials');
    }
}
