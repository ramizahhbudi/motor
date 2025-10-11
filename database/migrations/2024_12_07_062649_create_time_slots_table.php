<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeSlotsTable extends Migration
{
    public function up()
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mechanic_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['mechanic_id', 'date', 'start_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_slots');
    }
}
