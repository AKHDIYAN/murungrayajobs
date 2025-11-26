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
        Schema::table('pelatihan_peserta', function (Blueprint $table) {
            $table->string('file_sertifikat')->nullable()->after('alasan_mengikuti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelatihan_peserta', function (Blueprint $table) {
            $table->dropColumn('file_sertifikat');
        });
    }
};
