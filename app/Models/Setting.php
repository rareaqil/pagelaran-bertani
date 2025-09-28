<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = true;

    // Ambil value setting berdasarkan key
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

     // Ambil value setting, fallback ke env jika tidak ada
    public static function getEnvOrSetting(string $key, string $envKey, $default = null)
    {
        $value = self::getValue($key);
        if ($value === null || trim($value) === '') {
            return env($envKey, $default);
        }
        return $value;
    }
}
