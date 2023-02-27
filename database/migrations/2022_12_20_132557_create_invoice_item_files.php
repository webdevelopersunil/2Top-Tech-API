<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_item_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_item_id')->unsigned();
            $table->bigInteger('file_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items');
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
        Schema::dropIfExists('invoice_item_files');
    }
}
