<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Ayar değerini getir (cache'li)
     */
    public static function get(string $key, $default = null)
    {
        $setting = cache()->remember("setting.{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'number'  => (int) $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    /**
     * Ayar değerini kaydet ve cache'i temizle
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'group' => $group, 'type' => $type]
        );

        cache()->forget("setting.{$key}");
    }
}
