<?php

namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\DB;

class DBCourseRepository extends DBRepository implements CourseRepository
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function listCourse($request)
    {
        return $this->model
            ->join('item_class as ic', 'ic.id', 'courses.car_class_id')
            ->select('courses.*', 'ic.name as car_class')
            ->paginate($request->per_page);
    }

    public function allCourse($status=null)
    {
        $course = $this->model;
        if ($status) {
            $course = $course->where('status', $status);
        }
        return $course->get(['name as text', 'id']);
    }

    public function updateQuantity($qtt=1)
    {
        DB::table('courses')->increment('quantity', $qtt);
    }

}
