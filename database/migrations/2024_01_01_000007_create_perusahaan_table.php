<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nama_perusahaan');
            $table->foreignId('id_kecamatan')->constrained('kecamatan', 'id_kecamatan')->onDelete('cascade');
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('email')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('logo'); // REQUIRED: path logo perusahaan
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamp('tanggal_registrasi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
