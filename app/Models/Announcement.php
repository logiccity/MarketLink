<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'target_role',
        'status',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeActive($query, ?string $role = null)
    {
        $q = $query->where('status', 'published')
            ->where(function ($sub) {
                $sub->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($sub) {
                $sub->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });

        if ($role) {
            $q->where(function ($sub) use ($role) {
                $sub->where('target_role', 'all')
                    ->orWhere('target_role', $role);
            });
        }

        return $q;
    }
}
