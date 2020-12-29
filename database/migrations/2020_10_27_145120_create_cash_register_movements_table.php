<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('cash_register_movement_type_id');
            $table->unsignedBigInteger('cash_register_id');
            $table->unsignedBigInteger('turn_change_id');
            $table->unsignedBigInteger('payment_method_id')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount');
            $table->text('description')->nullable();
            $table->text('additional_info')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('cash_register_movement_type_id')->references('id')->on('cash_register_movement_types');
            $table->foreign('cash_register_id')->references('id')->on('cash_registers');
            $table->foreign('turn_change_id')->references('id')->on('turn_changes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
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
        Schema::dropIfExists('cash_register_movements');
    }
}
