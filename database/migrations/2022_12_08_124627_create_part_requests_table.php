<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->bigInteger('provider_id')->unsigned();
            $table->string('equipment_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->bigInteger('file_id')->unsigned();
            $table->string('make')->nullable();
            $table->string('model_no')->nullable();
            $table->string('sn_number')->nullable();
            $table->string('location')->nullable();
            $table->string('refrigerant_type')->nullable();
            $table->string('warranty_Info')->nullable();
            $table->string('voltage_amps')->nullable();
            $table->dateTime('date_of_purchase')->nullable();
            $table->string('filter_number')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status',['Pending','Fullfiled','NotAvailable'])->nullable()->default('Pending');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('part_requests');
    }
}
