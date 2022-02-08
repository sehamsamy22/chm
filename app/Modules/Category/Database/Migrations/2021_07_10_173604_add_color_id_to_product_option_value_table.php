<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorIdToProductOptionValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_option_value', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('value');
            $table->foreign('color_id')->on('product_colors')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_option_value', function (Blueprint $table) {
            $table->dropForeign('product_option_value_color_id_foreign');
            $table->dropColumn('color_id');
        });
    }
}
