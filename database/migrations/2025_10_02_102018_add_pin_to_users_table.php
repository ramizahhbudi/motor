<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // UBAH BARIS INI: dari float() menjadi string('pin', 6)
            $table->string('pin')->after('password');

            // Baris ini sudah benar
            $table->text('name')->change();
            $table->text('password')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pin');
            
            // Opsional tapi baik untuk ditambahkan: kembalikan tipe kolom seperti semula
            $table->string('name', 255)->change();
            $table->string('password', 255)->change();
        });
    }
};