<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistik', function (Blueprint $table) {
            $table->id('id_statistik');
            $table->foreignId('id_kecamatan')->constrained('kecamatan', 'id_kecamatan')->onDelete('cascade');
            $table->foreignId('id_pendidikan')->constrained('pendidikan', 'id_pendidikan')->onDelete('cascade');
            $table->foreignId('id_usia')->constrained('usia', 'id_usia')->onDelete('cascade');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Bekerja', 'Menganggur']);
            $table->foreignId('id_sektor')->nullable()->constrained('sektor', 'id_sektor')->onDelete('set null');
            $table->timestamps();
            
            // Index untuk performa filtering
            $table->index('id_kecamatan');
            $table->index('id_pendidikan');
            $table->index('id_usia');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistik');
    }
};
