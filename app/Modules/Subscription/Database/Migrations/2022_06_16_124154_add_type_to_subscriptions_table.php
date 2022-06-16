<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('type',['custom','normal']);
            $table->unsignedBigInteger('normal_subscription_id')->nullable();
            $table->foreign('normal_subscription_id')->on('normal_subscriptions')->references('id')->onDelete('cascade');
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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
            $table->dropForeign(['normal_subscription_id']);
            $table->dropColumn('normal_subscription_id');
        });
    }
}
