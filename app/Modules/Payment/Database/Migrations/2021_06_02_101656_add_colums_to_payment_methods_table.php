<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->after('image', function ($table) {
                $table->boolean('is_online')->default(0);
                $table->datetime('deactivated_at')->nullable();
            });
        });
        Schema::table('payment_credentials', function (Blueprint $table) {
            $table->after('name', function ($table) {
                $table->text('value')->nullable();
                $table->text('default')->nullable();
            });
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('is_online', 'deactivated_at');
        });
        Schema::table('payment_credentials', function (Blueprint $table) {
            $table->dropColumn('value', 'default');
        });
        Schema::table('payment_credentials', function (Blueprint $table) {
            $table->text('name')->nullable()->after('key');
        });
    }
}
