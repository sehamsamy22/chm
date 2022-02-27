<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackageToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type'); // enum column
        });
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type',['normal','subscription','service','additions','package_addition'])->after('SKU')->default('normal')->nullable();
            $table->boolean('is_package')->default(0);
            $table->integer('package_min')->default(0);
            $table->integer('package_max')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('is_package');
            $table->dropColumn('package_min');
            $table->dropColumn('package_max');

        });
    }
}
