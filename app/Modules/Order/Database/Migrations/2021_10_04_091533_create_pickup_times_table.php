<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_times', function (Blueprint $table) {
            $table->id();
            $table->enum('day',['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'])->nullable();
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->boolean('available')->default(1);
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
        Schema::dropIfExists('pickup_times');
    }
}
