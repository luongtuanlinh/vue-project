<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLogin extends Authenticatable
{
    use Notifiable, SoftDeletes;

    CONST TYPE_ADMIN = 1;
    CONST TYPE_TEACHER = 2;
    CONST TYPE_STUDENT = 3;

    protected $table = 'user_login';

    protected $fillable = [
        'name', 'loginID', 'password', 'type', 'user_id'
    ];


    protected $hidden = [
        'password',
    ];
}
