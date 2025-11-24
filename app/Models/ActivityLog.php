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

    // Helper methods for UI
    public function getBackgroundClass()
    {
        return match(true) {
            str_contains($this->action, 'view') => 'bg-gray-50',
            str_contains($this->action, 'create') || str_contains($this->action, 'approve') || str_contains($this->action, 'verify') => 'bg-green-50',
            str_contains($this->action, 'update') => 'bg-blue-50',
            str_contains($this->action, 'delete') || str_contains($this->action, 'reject') || str_contains($this->action, 'suspend') => 'bg-red-50',
            default => 'bg-purple-50'
        };
    }

    public function getIconClass()
    {
        return match(true) {
            str_contains($this->action, 'view') => 'bg-gray-500',
            str_contains($this->action, 'create') || str_contains($this->action, 'approve') || str_contains($this->action, 'verify') => 'bg-green-500',
            str_contains($this->action, 'update') => 'bg-blue-500',
            str_contains($this->action, 'delete') || str_contains($this->action, 'reject') || str_contains($this->action, 'suspend') => 'bg-red-500',
            default => 'bg-purple-500'
        };
    }

    public function getIcon()
    {
        $iconClass = 'w-5 h-5 text-white';
        
        return match(true) {
            str_contains($this->action, 'user') => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path></svg>',
            str_contains($this->action, 'company') => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path></svg>',
            str_contains($this->action, 'job') => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>',
            str_contains($this->action, 'application') || str_contains($this->action, 'lamaran') => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path></svg>',
            str_contains($this->action, 'dashboard') => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>',
            default => '<svg class="'.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };
    }
}
