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
        // Tabel Program Pelatihan
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id('id_pelatihan');
            $table->string('nama_pelatihan');
            $table->text('deskripsi');
            $table->unsignedBigInteger('id_sektor')->nullable();
            $table->string('penyelenggara');
            $table->string('instruktur')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi_hari');
            $table->integer('kuota_peserta');
            $table->enum('jenis_pelatihan', ['Online', 'Offline', 'Hybrid'])->default('Offline');
            $table->string('lokasi')->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('materi')->nullable(); // JSON array
            $table->enum('status', ['Dibuka', 'Ditutup', 'Berlangsung', 'Selesai'])->default('Dibuka');
            $table->boolean('sertifikat_tersedia')->default(true);
            $table->string('foto_banner')->nullable();
            $table->timestamps();

            $table->foreign('id_sektor')->references('id_sektor')->on('sektor')->onDelete('set null');
        });

        // Tabel Peserta Pelatihan
        Schema::create('pelatihan_peserta', function (Blueprint $table) {
            $table->id('id_peserta_pelatihan');
            $table->unsignedBigInteger('id_pelatihan');
            $table->unsignedBigInteger('id_user');
            $table->enum('status_pendaftaran', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->text('alasan_mengikuti')->nullable();
            $table->enum('status_kehadiran', ['Belum Dimulai', 'Hadir', 'Tidak Hadir'])->default('Belum Dimulai');
            $table->integer('persentase_kehadiran')->default(0);
            $table->boolean('lulus')->default(false);
            $table->integer('nilai')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pelatihan')->references('id_pelatihan')->on('pelatihan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->unique(['id_pelatihan', 'id_user']);
        });

        // Tabel Materi Pelatihan (Detail)
        Schema::create('pelatihan_materi', function (Blueprint $table) {
            $table->id('id_materi');
            $table->unsignedBigInteger('id_pelatihan');
            $table->string('judul_materi');
            $table->text('deskripsi_materi')->nullable();
            $table->integer('urutan')->default(1);
            $table->integer('durasi_menit')->nullable();
            $table->string('file_materi')->nullable();
            $table->timestamps();

            $table->foreign('id_pelatihan')->references('id_pelatihan')->on('pelatihan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_materi');
        Schema::dropIfExists('pelatihan_peserta');
        Schema::dropIfExists('pelatihan');
    }
};
