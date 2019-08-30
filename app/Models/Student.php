<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = ['mobile', 'name', 'status', 'email', 'birth_day', 'gender', 'code',
        'phone', 'passport', 'passport_date', 'passport_address', 'driver_address', 'driver_no', 'driver_class',
        'driver_date', 'course_id', 'program_id', 'teacher_id', 'order_id', 'fee', 'sub_fee', 'discount', 'has_picture', 'has_apply',
        'health_certification', 'health_certification_date', 'note', 'user_id'];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_student')->withPivot('fee');
    }
}
