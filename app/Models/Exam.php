<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = ['name', 'date', 'type', 'code', 'class_id', 'fee'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'exam_student')->withPivot('fee');
    }
}
