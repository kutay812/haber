<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    // Eğer tablo adı Laravel'in varsayılan isimlendirme kurallarına uyuyorsa bu satıra gerek yok
    protected $table = 'images';

    // Toplu atama (mass assignment) için izin verilen alanlar
    protected $fillable = [
        'name',
        'path',
    ];

    /**
     * Bir resim birden fazla haberle ilişkili olabilir.
     * Eğer resim birden fazla haberle ilişkilendiriliyorsa belongsToMany kullanılır.
     */
    public function news()
    {
        return $this->belongsToMany(News::class);
    }
}
