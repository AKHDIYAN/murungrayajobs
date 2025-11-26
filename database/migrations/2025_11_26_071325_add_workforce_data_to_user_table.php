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
        Schema::table('user', function (Blueprint $table) {
            // Status Kerja untuk tracking apakah user sedang bekerja/menganggur
            $table->enum('status_kerja', ['Menganggur', 'Bekerja', 'Tidak Aktif'])
                  ->default('Menganggur')
                  ->after('email');
            
            // Pekerjaan saat ini (jika bekerja)
            $table->string('pekerjaan_saat_ini')->nullable()->after('status_kerja');
            
            // Pengalaman kerja (dalam tahun)
            $table->integer('pengalaman_kerja')->default(0)->after('pekerjaan_saat_ini');
            
            // Jenis sertifikasi yang dimiliki (JSON array)
            $table->json('jenis_sertifikasi')->nullable()->after('sertifikat');
            
            // Skills/Kompetensi (JSON array)
            $table->json('skills')->nullable()->after('jenis_sertifikasi');
            
            // Status verifikasi sertifikat
            $table->boolean('sertifikat_verified')->default(false)->after('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn([
                'status_kerja',
                'pekerjaan_saat_ini',
                'pengalaman_kerja',
                'jenis_sertifikasi',
                'skills',
                'sertifikat_verified'
            ]);
        });
    }
};
