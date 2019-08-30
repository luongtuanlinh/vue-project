<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'phone', 'email', 'role'];

    CONST ROLE_ADMIN = 1;
    CONST ROLE_TEACHER = 2;

    public static function userProgram($type)
    {
        switch ($type) {
            case self::ROLE_ADMIN:
                return 'G1';
                break;
            case self::ROLE_TEACHER:
                return 'G0';
                break;
        }
    }
}
