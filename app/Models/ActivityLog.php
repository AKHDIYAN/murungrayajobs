<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Static method untuk create log
    public static function createLog($userType, $userId, $action, $description)
    {
        return self::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Scopes
    public function scopeByUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
