<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;


#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'role',
    'telepon',
    'alamat',
    'status',
    'last_login_at',
])]

#[Hidden([
    'password',
    'remember_token'
])]

class User extends Authenticatable
{
   use HasFactory, Notifiable;

    public function pelanggan(): HasOne
    {
    return $this->hasOne(
        Pelanggan::class,
        'user_id'
    );
    }
    public function teknisi(): HasOne
    {
       return $this->hasOne(
            Teknisi::class
        );
    }
    public function notificationSetting(): HasOne
       {
        return $this->hasOne(
            NotificationSetting::class
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];

    }

}