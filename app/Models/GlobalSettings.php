<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    use HasFactory;

    protected $table = 'global_settings';

    public $timestamps = false;

    protected $guarded = [];

    static public function get($key)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return null;
    }

    static public function getAllOptions()
    {
        $settings = self::all();
        $options = [];
        foreach ($settings as $setting) {
            $options[$setting->key] = $setting->value;
        }
        return $options;
    }

    static public function set($key, $value)
    {
        $setting = self::where('key', $key)->first();

        $value = is_array($value) ? json_encode($value) : $value;

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
