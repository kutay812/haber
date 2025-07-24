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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the news created by the user.
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function getProfileImageUrlAttribute()
    {
        // Eğer kullanıcı bir resim yüklemişse onu döndür
        if ($this->profile_image) {
            return asset('storage/profile-image/' . $this->profile_image);
        }
        // Yoksa default profil resmi döndür
        return asset('storage/adminlte/dist/img/user2-160x160.jpg');
    }
    protected static function booted()
{
    static::created(function ($user) {
        // Eğer kullanıcıya rol atanmamışsa User rolü ver
        if (!$user->roles()->count()) {
            $user->assignRole('User');
        }
    });
}

}
