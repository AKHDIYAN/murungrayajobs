<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id('id_pekerjaan');
            $table->foreignId('id_perusahaan')->constrained('perusahaan', 'id_perusahaan')->onDelete('cascade');
            $table->foreignId('id_kecamatan')->constrained('kecamatan', 'id_kecamatan')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('sektor', 'id_sektor')->onDelete('cascade');
            $table->string('nama_pekerjaan');
            $table->string('nama_perusahaan');
            $table->decimal('gaji_min', 15, 2);
            $table->decimal('gaji_max', 15, 2);
            $table->text('deskripsi_pekerjaan');
            $table->text('persyaratan_pekerjaan');
            $table->text('benefit')->nullable();
            $table->integer('jumlah_lowongan')->default(1);
            $table->enum('jenis_pekerjaan', ['Full-Time', 'Part-Time', 'Kontrak']);
            $table->date('tanggal_expired'); // WAJIB: tanggal berakhir lowongan
            $table->enum('status', ['Diterima', 'Pending', 'Ditolak'])->default('Pending');
            $table->timestamp('tanggal_posting')->useCurrent();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('id_perusahaan');
            $table->index('id_kecamatan');
            $table->index('id_kategori');
            $table->index('tanggal_expired');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
    }
};
