<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreHouseMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_house_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_house_id');
            $table->unsignedBigInteger('store_house_movement_type_id');
            $table->text('description')->nullable();
            $table->foreign('store_house_id')->references('id')->on('store_houses');
            $table->foreign('store_house_movement_type_id')->references('id')->on('store_house_movement_types');
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
        Schema::dropIfExists('store_house_movements');
    }
}
