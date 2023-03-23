<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('customer_id')->nullable();
            $table->double('discount')->nullable()->default('0');
            $table->double('total_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->string('balance_transaction')->nullable();
            $table->string('currency',100)->nullable();
            $table->string('txn_id', 100)->nullable();
            $table->string('payment_status', 20)->nullable()->comment('pending, paid , failed');
            $table->text('other_transaction_detail')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
