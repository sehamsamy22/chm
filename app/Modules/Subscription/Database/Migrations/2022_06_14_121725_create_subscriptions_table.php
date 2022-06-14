<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('price')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->on('subscription_sizes')->references('id')->onDelete('cascade');
            //----------------------------------------------------------
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->on('subscription_types')->references('id')->onDelete('cascade');
            //------------------------------------------------------------
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->foreign('delivery_id')->on('subscription_delivery_counts')->references('id')->onDelete('cascade');
            //-------------------------------------------------------------
            $table->unsignedBigInteger('wrapping_type_id')->nullable();
            $table->foreign('wrapping_type_id')->on('wrapping_types')->references('id')->onDelete('cascade');
            //------------------------------------------------------------
            $table->unsignedBigInteger('day_count_id')->nullable();
            $table->foreign('day_count_id')->on('subscription_day_counts')->references('id')->onDelete('cascade');
            //------------------------------------------------------------
            $table->unsignedBigInteger('time_id')->nullable();
            $table->foreign('time_id')->on('pickup_times')->references('id')->onDelete('cascade');
            //------------------------------------------------------------

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
        Schema::dropIfExists('subscriptions');
    }
}
