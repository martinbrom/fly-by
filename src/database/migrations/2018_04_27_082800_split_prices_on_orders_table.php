<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SplitPricesOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('orders', function (Blueprint $table) {
	    	$table->integer('flight_price')->unsigned()->after('price');
	    	$table->integer('transport_price')->nullable()->unsigned()->after('flight_price');
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
	    	$table->dropColumn('flight_price');
	    	$table->dropColumn('transport_price');
	    });
    }
}
