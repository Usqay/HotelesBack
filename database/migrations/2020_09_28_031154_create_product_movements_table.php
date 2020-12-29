<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_house_movement_id');
            $table->unsignedBigInteger('product_movement_type_id');
            $table->decimal('quantity');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('store_house_movement_id')->references('id')->on('store_house_movements');
            $table->foreign('product_movement_type_id')->references('id')->on('product_movement_types');
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
        Schema::dropIfExists('product_movements');
    }
}
