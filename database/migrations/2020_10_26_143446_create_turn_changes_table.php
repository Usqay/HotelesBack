<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turn_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turn_id');
            $table->unsignedBigInteger('open_by_user_id');
            $table->unsignedBigInteger('close_by_user_id')->nullable();
            $table->unsignedBigInteger('currency_base_id');
            $table->timestamp('open_date')->useCurrent();
            $table->timestamp('close_date')->nullable();
            $table->boolean('status_active')->default(true);
            $table->foreign('turn_id')->references('id')->on('turns');
            $table->foreign('open_by_user_id')->references('id')->on('users');
            $table->foreign('close_by_user_id')->references('id')->on('users');
            $table->foreign('currency_base_id')->references('id')->on('currencies');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turn_changes');
    }
}
