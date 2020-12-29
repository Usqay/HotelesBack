<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectronicVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electronic_vouchers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_emitted')->useCurrent();
            $table->unsignedBigInteger('electronic_voucher_type_id');
            $table->bigInteger('number');
            $table->char('serie', 5)->nullable();
            $table->boolean('print')->default(true);
            $table->text('api_body');
            $table->text('api_response')->nullable();
            $table->char('api_state', 1)->default(1); //1 is aceppted, 0 is not aceppted, 2 is not sent
            $table->text('adittional_info')->nullable();
            $table->foreign('electronic_voucher_type_id')->references('id')->on('electronic_voucher_types');
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
        Schema::dropIfExists('electronic_vouchers');
    }
}
