<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('print_queues', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title');
        //     $table->string('subtitle');
        //     $table->boolean('show_logo')->default(true);
        //     $table->boolean('show_business_info')->default(true);
        //     $table->boolean('show_people_info')->default(true);
        //     $table->boolean('show_items')->default(true);
        //     $table->unsignedBigInteger('people_id')->nullable();
        //     $table->text('additional_header_info')->nullable();
        //     $table->text('items_headers')->nullable();
        //     $table->text('items_values')->nullable();
        //     $table->text('qr_code');
        //     $table->foreign('people_id')->references('id')->on('people');
        //     $table->timestamps();
        //     $table->softDeletes('deleted_at');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('print_queues');
    }
}
