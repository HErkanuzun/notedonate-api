<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'json' // JSON tipindeki değerler için otomatik dönüşüm
    ];

    /**
     * Belirli bir ayarı getir
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Ayar değerini güncelle veya oluştur
     */
    public static function set($key, $value, $attributes = [])
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        
        foreach ($attributes as $attr => $val) {
            $setting->{$attr} = $val;
        }

        return $setting->save();
    }

    /**
     * Belirli bir gruptaki tüm ayarları getir
     */
    public static function group($group)
    {
        return static::where('group', $group)->get();
    }
}
