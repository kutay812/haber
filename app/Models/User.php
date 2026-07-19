<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // HABERLER: Kullanıcının oluşturduğu haberler
    public function news()
    {
        return $this->hasMany(News::class);
    }

    // YORUMLAR: Kullanıcının yaptığı yorumlar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Profil resmi yolunu döndürür
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('storage/adminlte/dist/img/user2-160x160.jpg');
    }

    // Otomatik User rolü ataması
    protected static function booted()
    {
        static::created(function ($user) {
            if (!$user->roles()->count()) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'User']);
                $user->assignRole('User');
            }
        });
    }
}
