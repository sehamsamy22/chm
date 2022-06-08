<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdToSubscriptionSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_sizes', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->on('stores')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_sizes', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
}
