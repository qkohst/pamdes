<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status',
        'avatar',
        'is_delete'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getLevelUserAttribute()
    {
        if ($this->role == 1) {
            $level_user = 'Administrator';
        } elseif ($this->role == 2) {
            $level_user = 'Pelanggan';
        }
        return $level_user;
    }

    public function getStatusUserAttribute()
    {
        if ($this->status == 1) {
            $status_str = 'Aktif';
        } elseif ($this->status == 0) {
            $status_str = 'Non Aktif';
        }
        return $status_str;
    }
}
