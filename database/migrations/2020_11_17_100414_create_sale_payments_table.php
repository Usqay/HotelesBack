<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('cash_register_movement_id');
            $table->unsignedBigInteger('electronic_voucher_id')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('people_id')->nullable();
            $table->decimal('total');
            $table->boolean('print_payment', 1)->default(true);
            $table->char('document_type', 3)->default('not');
            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('cash_register_movement_id')->references('id')->on('cash_register_movements');
            $table->foreign('electronic_voucher_id')->references('id')->on('electronic_vouchers');
            $table->foreign('people_id')->references('id')->on('people');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_payments');
    }
}
