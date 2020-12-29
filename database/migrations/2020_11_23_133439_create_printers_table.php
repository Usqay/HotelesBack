<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('printer_type_id');
            $table->string('name');
            $table->integer('port')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
            $table->foreign('printer_type_id')->references('id')->on('printer_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printers');
    }
}
