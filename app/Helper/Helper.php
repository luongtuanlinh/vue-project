<?php

namespace App\Helper;

class Helper
{
    public static function formatDate($date)
    {
        if (!$date) return null;
        $d = explode('/', $date);
        return "{$d[2]}-{$d[1]}-{$d[0]}";
    }

    public static function parseDate($date)
    {
        if (!$date) return null;
        return date('d/m/Y', strtotime($date));
    }
}
