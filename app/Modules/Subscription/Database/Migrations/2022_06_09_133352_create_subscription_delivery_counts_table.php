<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionDeliveryCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_delivery_counts', function (Blueprint $table) {
            $table->id();
            $table->string('count');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->on('subscription_types')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('subscription_delivery_counts');
    }
}
