<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('block_ledgers', function (Blueprint $table) {
            $table->id();
            $table->longText('data');
            $table->timestamp('timestamp');
            $table->string('previous_hash')->nullable();
            $table->string('current_hash');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('block_ledgers');
    }
};
