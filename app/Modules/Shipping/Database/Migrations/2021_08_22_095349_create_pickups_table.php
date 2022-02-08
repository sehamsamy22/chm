<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('method_id')->unsigned();
            $table->foreign('method_id')->on('shipping_methods')->references('id')->onDelete('cascade');
            $table->dateTime('pickup_time')->nullable();
            $table->boolean('status')->default(1);
            $table->string('shipping_id')->nullable();
            $table->string('shipping_guid')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('pickups');
    }
}
