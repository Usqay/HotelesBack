<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('token_for_observer')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->unsignedBigInteger('client_id') ->nullable();
            $table->unsignedBigInteger('reservation_origin_id');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('reservation_state_id');
            $table->unsignedBigInteger('turn_change_id');
            $table->integer('total_days');
            $table->integer('total_hours');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('reservation_origin_id')->references('id')->on('reservation_origins');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('reservation_state_id')->references('id')->on('reservation_states');
            $table->foreign('turn_change_id')->references('id')->on('turn_changes');
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
        Schema::dropIfExists('reservations');
    }
}
