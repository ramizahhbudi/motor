<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mechanic_id');
            $table->unsignedBigInteger('motor_id');
            $table->unsignedBigInteger('time_slot_id');
            $table->string('booking_id')->unique(); // Add booking_id
            $table->string('status')->default('booked'); // Change default status to 'booked'
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('motor_id')->references('id')->on('kepemilikan_motors')->onDelete('cascade');
            $table->foreign('time_slot_id')->references('id')->on('time_slots')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
