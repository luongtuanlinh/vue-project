<?php

namespace App\Repositories\Eloquent;

use App\Models\Exam;
use App\Models\Student;
use App\Models\UserLogin;
use App\Repositories\StudentRepository;
use Illuminate\Support\Facades\DB;

class DBStudentRepository extends DBRepository implements StudentRepository
{

    public function __construct(Student $model, Exam $exam)
    {
        $this->exam = $exam;
        parent::__construct($model);
    }


    public function studentsInTeacher( $args = [] )
    {
        $query = $this->model;
        if ( isset($args['oid']) && !empty($args['oid']) ) {
            $query = $query->where('order_id',  $args['oid']);
        }
        return $query->select(
            'students.*'
        )->get();
    }

    public function store(array $data, $id = null)
    {
        $student = $this->create($data);
        return $student->id;
    }

    public function list(array $params=[])
    {
        $query = $this->model->join('courses as c', 'c.id', 'students.course_id')->join('programs as p', 'p.id', 'students.program_id');

        if (isset($params['name']) && !empty($params['name'])) {
            $query = $query->where('students.name', 'like', "%{$params['name']}%");
        }
        if (isset($params['code']) && !empty($params['code'])) {
            $query = $query->where('students.code', 'like', "%{$params['code']}%");
        }
        if (isset($params['email']) && !empty($params['email'])) {
            $query = $query->where('students.email', 'like', "%{$params['email']}%");
        }
        if (isset($params['mobile']) && !empty($params['mobile'])) {
            $query = $query->where('students.mobile', 'like', "%{$params['mobile']}%");
        }
        if (isset($params['course_id']) && !empty($params['course_id'])) {
            $query = $query->where('students.course_id', $params['course_id']);
        }
        if (isset($params['program']) && !empty($params['program'])) {
            $query = $query->where('p.name', $params['program']);
        }
        if (isset($params['class_id'])) {
            if ($params['class_id']==-1) {
                $query = $query->where('students.class_id', null);
            } else {
                $query = $query->where('students.class_id', $params['class_id']);
            }
        }

        //hoc vien thi lai
        if (isset($params['exam_1']) && $params['exam_1']==2) {

        }

        //khoa hoc dang dien ra
        if (isset($params['exam_1']) && $params['exam_1']==1) {

        }

        return $query->select(
                'students.*',
                'c.name as course', 'p.name as program'
            )
                ->paginate($params['per_page']);
    }

    public function listAddExam($params=[])
    {
        //hoc vien da dc add vao danh sach thi
        $students_in_exam = $this->exam->find($params['exam_id'])->students()->pluck('id')->toArray();

        $query = $this->model->join('courses as c', 'c.id', 'students.course_id')->join('programs as p', 'p.id', 'students.program_id');

        //hoc vien thi lai
        if (isset($params['exam_1']) && $params['exam_1']==2) {

        }

        //khoa hoc dang dien ra
        if (isset($params['exam_1']) && $params['exam_1']==1) {

        }

        return $query->whereNotIn('students.id', $students_in_exam)     //bỏ những hv đã có ds thi
            ->select(
            'students.*',
            'c.name as course', 'p.name as program'
        )
            ->paginate($params['per_page']);
    }

    /**
     * kiểm tra thông tin bị trùng đăng ký trong khóa học (số CMT, sđt, email)
     * @param array $params
     * @return mixed
     */
    public function checkExistInCourse(array $params)
    {
        $info = $this->model
            ->where('course_id', $params['course_id'])
            ->where(function ($query) use ($params) {
                $q = $query->orWhere('passport', $params['passport']);

                if ( !empty($params['mobile']) ) {
                    $q = $query->where('mobile', $params['mobile']);
                }
                if ( !empty($params['email']) ) {
                    $q = $query->orWhere('email', $params['email']);
                }
            })
            ->first();

        return $info;
    }

    public function getInfo($id)
    {
        return DB::table('students as s')->leftJoin('users as u', 'u.id', 's.teacher_id')
            ->where('s.id', $id)
            ->select(['s.*', 'u.name as teacher'])
            ->first();
    }

    public function delete($id)
    {
        $student = $this->model->find($id);
        $this->model->destroy($id);
        DB::table('user_login')->where('user_id', $id)->where('type', UserLogin::TYPE_STUDENT)->delete();

        if ( $student->order_id ) {
            DB::table('orders')->where('id', $student->order_id)->decrement('applied');
        }
    }

}
