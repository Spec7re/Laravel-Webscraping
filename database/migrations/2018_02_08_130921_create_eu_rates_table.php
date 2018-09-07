<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEuRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('euribor_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('euribor_date_id')->unsigned()->index();
            $table->string('duration');
            $table->float('rate',8,4);
        });

        Schema::create('euribor_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('for_date');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('euribor_rates');
        Schema::dropIfExists('euribor_dates');
    }
}
