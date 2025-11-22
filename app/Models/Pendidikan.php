<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikan';
    protected $primaryKey = 'id_pendidikan';

    protected $fillable = [
        'tingkatan_pendidikan',
    ];

    // Relationships
    public function statistik()
    {
        return $this->hasMany(Statistik::class, 'id_pendidikan', 'id_pendidikan');
    }
}
