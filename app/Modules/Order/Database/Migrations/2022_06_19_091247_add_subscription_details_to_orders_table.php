<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionDetailsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->on('subscription_types')->references('id')->onDelete('cascade');
            //------------------------------------------------------------
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->foreign('delivery_id')->on('subscription_delivery_counts')->references('id')->onDelete('cascade');
            //-------------------------------------------------------------
//            $table->unsignedBigInteger('wrapping_type_id')->nullable();
//            $table->foreign('wrapping_type_id')->on('wrapping_types')->references('id')->onDelete('cascade');
            //------------------------------------------------------------
            $table->unsignedBigInteger('day_count_id')->nullable();
            $table->foreign('day_count_id')->on('subscription_day_counts')->references('id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

        });
    }
}
