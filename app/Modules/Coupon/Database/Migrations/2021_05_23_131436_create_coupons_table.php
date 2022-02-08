<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->string('prom_code');
            $table->enum('type',['percent', 'amount']);
			$table->datetime('start_date');
			$table->datetime('end_date');
			$table->enum('prom_time', ['once', 'daily', 'weekly', 'monthly']);
			$table->integer('used_count')->default(0);
			$table->datetime('deactivated_at')->nullable();
			$table->softDeletes();
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
        Schema::dropIfExists('coupons');
    }
}
