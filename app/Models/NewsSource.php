<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'type',
        'logo_url',
        'website_url',
        'reliability_score',
        'default_category_id',
        'is_active',
        'auto_publish',
        'fetch_interval_minutes',
        'last_fetched_at',
        'total_fetched',
        'failed_fetches',
        'category_mappings',
        'notes',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'auto_publish'         => 'boolean',
        'reliability_score'    => 'integer',
        'fetch_interval_minutes' => 'integer',
        'total_fetched'        => 'integer',
        'failed_fetches'       => 'integer',
        'last_fetched_at'      => 'datetime',
        'category_mappings'    => 'array',
    ];

    // KAYNAK > HABERLER ilişkisi
    public function news()
    {
        return $this->hasMany(News::class);
    }

    // KAYNAK > VARSAYILAN KATEGORİ ilişkisi
    public function defaultCategory()
    {
        return $this->belongsTo(Category::class, 'default_category_id');
    }

    // Scope: Aktif kaynaklar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Güncelleme zamanı geldi mi?
    public function shouldFetch(): bool
    {
        if (!$this->is_active) return false;
        if (!$this->last_fetched_at) return true;

        return $this->last_fetched_at->addMinutes($this->fetch_interval_minutes)->isPast();
    }

    // Güvenilirlik seviyesi
    public function getReliabilityLevelAttribute(): string
    {
        return match (true) {
            $this->reliability_score >= 90 => 'very_high',
            $this->reliability_score >= 70 => 'high',
            $this->reliability_score >= 50 => 'medium',
            $this->reliability_score >= 30 => 'low',
            default => 'very_low',
        };
    }

    // Slug otomatik oluşturma
    protected static function booted()
    {
        static::creating(function ($source) {
            if (empty($source->slug)) {
                $source->slug = \Illuminate\Support\Str::slug($source->name);
            }
        });
    }
}
