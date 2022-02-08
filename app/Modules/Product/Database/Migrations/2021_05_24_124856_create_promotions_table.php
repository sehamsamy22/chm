<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('discount');
            $table->integer('paid_quantity')->nullable();
            $table->integer('discounted_quantity')->nullable();
            $table->timestamp('start_date');
            $table->dateTime('expiration_date');
            $table->dateTime('deactivated_at')->nullable();
            $table->unsignedBigInteger('list_id')->nullable();
            $table->foreign('list_id')->on('lists')->references('id')->onDelete('set null');
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
        Schema::dropIfExists('promotions');
    }
}
