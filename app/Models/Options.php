<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Options extends Model
{
    protected $table = 'options';

    public static function getValue($key)
    {
        return Self_::where('key', $key)->select('value')->first();
    }

    public static function getValues($keys=[])
    {
        $options = self::whereIn('key', $keys)->get(['key', 'value'])->toArray();
        $settings = [];
        foreach ($options as $option) {
            $settings[$option['key']] = $option['value'];
        }

        return $settings;
    }

    public function getAll()
    {
        $options = self::all(['key', 'value'])->toArray();
        $settings = [];
        foreach ($options as $option) {
            $settings[$option['key']] = $option['value'];
        }

        return $settings;
    }

    public function set($key, $value)
    {
        self::where('key', $key)->update(['value'=>$value]);
    }
}
