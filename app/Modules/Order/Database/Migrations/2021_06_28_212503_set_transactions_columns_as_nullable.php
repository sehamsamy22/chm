<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetTransactionsColumnsAsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('order_details')->nullable()->change();
            $table->text('payment_reference')->nullable()->change();
            $table->text('card_info')->nullable()->change();
            $table->text('transaction_response')->nullable()->change();
            $table->text('response_code')->nullable()->change();
            $table->text('transaction_status')->nullable()->change();
            $table->decimal('total_amount', 8, 2)->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
