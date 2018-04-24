<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAirportsToRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('routes', function (Blueprint $table) {
		    $table->integer('airport_from_id')->unsigned()->after('id');
		    $table->integer('airport_to_id')->unsigned()->after('airport_from_id');
	    });

	    Schema::table('routes', function (Blueprint $table) {
		    $table->foreign('airport_from_id')->references('id')->on('airports');
		    $table->foreign('airport_to_id')->references('id')->on('airports');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('routes', function (Blueprint $table) {
		    $table->dropForeign(['airport_from_id']);
		    $table->dropForeign(['airport_to_id']);
		    $table->dropColumn('airport_from_id');
		    $table->dropColumn('airport_to_id');
	    });
    }
}
