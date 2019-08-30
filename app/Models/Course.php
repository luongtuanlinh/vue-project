<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;
    protected $table = 'courses';
    protected $fillable = ['car_class_id', 'name', 'status', 'max', 'start_time', 'end_time', 'exam_time', 'exam_end_time'];

    CONST AVAILABLE = 1;
    CONST UNAVAILABLE = 0;

    CONST DANG_TUYEN = 1;
    CONST NGUNG_TUYEN = 2;      //đang diễn ra
    CONST KET_THUC = 3;

}
