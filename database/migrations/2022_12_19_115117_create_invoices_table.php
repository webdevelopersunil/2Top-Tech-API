<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('invoice_number');
            $table->bigInteger('booking_id')->unsigned();
            $table->bigInteger('provider_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->string('billing_name');
            $table->string('billing_address');
            $table->bigInteger('service_id');
            $table->double('sub_total',10,2);
            $table->double('tax',10,2);
            $table->double('total_amount',10,2);
            $table->enum('status',['Pending','Paid']);
            $table->foreign('booking_id')->references('id')->on('job_bookings');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
