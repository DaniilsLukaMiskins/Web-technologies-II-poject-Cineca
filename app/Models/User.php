<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function statistics()
    {
        return $this->hasOne(UserStatistic::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }


    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role->name, ['moderator', 'admin']);
    }
}