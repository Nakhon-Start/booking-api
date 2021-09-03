<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->integer('booker_id');
            $table->integer('checker_id')->nullable();
            $table->integer('room_id');
            $table->string('booker_note');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('booking_status')->default(-1);
            $table->string('checker_note')->nullable();
            $table->timestamps();
        });
    }


    /*
        is_active -1 = รออนุมัติ / 1 = ผ่าน / 2 = ไม่ผ่าน
    */


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
}
