<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nik', 16)->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->foreignId('id_kecamatan')->constrained('kecamatan', 'id_kecamatan')->onDelete('cascade');
            $table->string('no_telepon', 15);
            $table->string('email')->unique();
            $table->string('foto'); // REQUIRED: path foto profil
            $table->string('cv')->nullable();
            $table->string('ktp')->nullable();
            $table->string('sertifikat')->nullable();
            $table->string('foto_diri')->nullable();
            $table->rememberToken();
            $table->timestamp('tanggal_registrasi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
