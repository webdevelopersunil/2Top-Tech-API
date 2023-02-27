<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('booking_id')->unsigned();
            $table->string('rating_type');
            $table->text('rating_comment');
            $table->integer('rate');
            $table->bigInteger('rating_by')->unsigned();
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('job_bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
