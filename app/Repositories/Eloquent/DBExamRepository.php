<?php

namespace App\Repositories\Eloquent;

use App\Models\Exam;
use App\Models\Student;
use App\Repositories\ExamRepository;
use Illuminate\Support\Facades\DB;

class DBExamRepository extends DBRepository implements ExamRepository
{
    public function __construct(Exam $model, Student $student)
    {
        $this->student = $student;
        parent::__construct($model);
    }

    public function findById($id, $cols=['*'])
    {
        return $this->model->join('item_class as c', 'c.id', 'exams.class_id')
            ->select('exams.*', 'c.name as class')
            ->where('exams.id', $id)
            ->withCount('students')
            ->first();
    }

    public function examPaginate($per=10)
    {
        return $this->model->join('item_class as c', 'c.id', 'exams.class_id')->orderBy('exams.id', 'desc')->select('exams.*', 'c.name as class')->withCount('students')->paginate($per);
    }

    public function getStudents($id, $params=[])
    {
        $student_ids = $this->model->find($id)->students()->pluck('id')->toArray();
        $students = DB::table('students as st')
            ->join('courses as c', 'c.id', 'st.course_id')
            ->join('programs as p', 'p.id', 'st.program_id')
            ->join('exam_student as e', 'e.student_id', 'st.id')
            ->whereIn('st.id', $student_ids)
            ->where('e.exam_id', $id)
            ->select(
                'st.name', 'st.id', 'st.code', 'st.birth_day',
                'e.fee',
                'c.name as course', 'p.name as program'
            )
            ->paginate($params['per_page']);

        return $students;
    }

    public function allStudent($id, $params=[])
    {
        $student_ids = $this->model->find($id)->students()->pluck('id')->toArray();
        $students = DB::table('students as st')
            ->join('courses as c', 'c.id', 'st.course_id')
            ->join('programs as p', 'p.id', 'st.program_id')
            ->join('exam_student as e', 'e.student_id', 'st.id')
            ->whereIn('st.id', $student_ids)
            ->where('e.exam_id', $id)
            ->select(
                'st.name', 'st.code', 'st.birth_day',
                'c.name as course', 'p.name as program'
            );

        return $students->get();
    }
}