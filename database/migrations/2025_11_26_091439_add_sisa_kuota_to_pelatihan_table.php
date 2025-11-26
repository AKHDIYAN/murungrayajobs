<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->integer('sisa_kuota')->default(0)->after('kuota_peserta');
        });

        // Sync existing data: sisa_kuota = kuota_peserta - jumlah_peserta
        DB::statement('UPDATE pelatihan SET sisa_kuota = kuota_peserta - (SELECT COUNT(*) FROM pelatihan_peserta WHERE pelatihan_peserta.id_pelatihan = pelatihan.id_pelatihan)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn('sisa_kuota');
        });
    }
};
