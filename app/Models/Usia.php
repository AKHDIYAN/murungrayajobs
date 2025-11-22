<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usia extends Model
{
    use HasFactory;

    protected $table = 'usia';
    protected $primaryKey = 'id_usia';

    protected $fillable = [
        'kelompok_usia',
    ];

    // Relationships
    public function statistik()
    {
        return $this->hasMany(Statistik::class, 'id_usia', 'id_usia');
    }
}
