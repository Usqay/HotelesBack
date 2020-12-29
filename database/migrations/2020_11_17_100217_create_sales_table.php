<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('token_for_observer')->nullable();
            $table->unsignedBigInteger('client_id') ->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('sale_state_id');
            $table->unsignedBigInteger('turn_change_id');
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('store_house_movement_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('sale_state_id')->references('id')->on('sale_states');
            $table->foreign('turn_change_id')->references('id')->on('turn_changes');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('store_house_movement_id')->references('id')->on('store_house_movements');
            $table->foreign('room_id')->references('id')->on('rooms');
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
        Schema::dropIfExists('sales');
    }
}
