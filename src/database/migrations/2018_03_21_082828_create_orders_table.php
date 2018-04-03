<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('price')->unsigned();
            $table->string('code', 32);
            $table->integer('route_id')->unsigned();
            $table->integer('aircraft_airport_id')->unsigned()->nullable();
            $table->timestamps();

            $table->unique('id');
            $table->unique('code');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->foreign('aircraft_airport_id')->references('id')->on('aircraft_airport_xref');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
