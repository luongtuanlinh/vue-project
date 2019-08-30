<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\ApplyRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\UserRequest;
use App\Models\UserLogin;
use App\Repositories\CourseRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\StudentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserLoginRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends AdminController
{
    public $studentRepository;
    public $userLoginRepository;
    public $orderRepository;
    public $programRepository;
    public $courseRepository;

    public function __construct(StudentRepository $studentRepository,
                                UserLoginRepository $userLoginRepository,
                                ProgramRepository $programRepository,
                                CourseRepository $courseRepository,
                                OrderRepository $orderRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->userLoginRepository = $userLoginRepository;
        $this->orderRepository = $orderRepository;
        $this->programRepository = $programRepository;
        $this->courseRepository = $courseRepository;
    }

    public function studentsInTeacher(Request $request) {
        $all = $this->studentRepository->studentsInTeacher($request->all());
        return $this->response(true, '', $all);
    }

    public function list(Request $request)
    {
        if( isset($request->exam) ) {
            $list = $this->studentRepository->listAddExam($request->all());
        } else {
            $list = $this->studentRepository->list($request->all());
        }

        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $student = (array) $this->studentRepository->getInfo($id);
        $student = array_merge($student, [
            'birth_day' => Helper::parseDate($student['birth_day']),
            'driver_date' => Helper::parseDate($student['driver_date']),
            'passport_date' => Helper::parseDate($student['passport_date']),
        ]);
        return $this->response(true, '', $student);
    }

    public function store(StudentRequest $request)
    {
        $data = $request->all();
        if ($this->studentRepository->checkExistInCourse($data)) {
            return $this->response(false, 'thông tin học viên đã tồn tại trong khóa học. vui lòng kiểm tra lại.');
        }

        $data = array_merge($data, [
            'code'  => time(),
            'user_id'   => Auth::id(),
            'birth_day' => Helper::formatDate($data['birth_day']),
            'passport_date' => Helper::formatDate($data['passport_date']),
            'driver_date' => Helper::formatDate($data['driver_date']),
            'health_certification_date' => Helper::formatDate($data['health_certification_date']),
        ]);
        DB::beginTransaction();
        try {
            $user_id = $this->studentRepository->store($data);
            $this->userLoginRepository->storeUser(['user_id'=>$user_id, 'loginID'=>$data['code']], UserLogin::TYPE_STUDENT);
            DB::commit();
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('Create new student: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function apply(ApplyRequest $request) {
        $data = $request->all();

        //lay thong tin yeu cau cua giao vien
        $order = $this->orderRepository->findById($data['oid']);

        unset($data['oid']);
        $data = array_merge($data, [
            'teacher_id' => $order->user_id,
            'order_id'  => $order->id,
            'course_id' => $order->course_id
        ]);

        if ($this->studentRepository->checkExistInCourse($data)) {
            return $this->response(false, 'thông tin học viên đã tồn tại trong khóa học. vui lòng kiểm tra lại.');
        }

        $program = $this->programRepository->findBy('name', 'G0');

        $data = array_merge($data, [
            'program_id'    => $program->id,
            'code'  => time(),
            'user_id'   => Auth::id(),
            'birth_day' => Helper::formatDate($data['birth_day']),
            'passport_date' => Helper::formatDate($data['passport_date']),
            'driver_date' => Helper::formatDate($data['driver_date']),
            'health_certification_date' => Helper::formatDate($data['health_certification_date']),
        ]);
        DB::beginTransaction();
        try {
            $this->studentRepository->store($data);
            $this->orderRepository->updateQuantity();
            DB::commit();
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('Create new student: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function update(UserRequest $request)
    {
        $data = $request->only('name', 'phone', 'email');
        DB::beginTransaction();
        try {
            $this->studentRepository->store($data, $request->id);
            DB::commit();
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('update teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $student = $this->studentRepository->findById($request->id);

        if ( !$student ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        DB::beginTransaction();
        try{
            $this->studentRepository->delete($request->id);
            DB::commit();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }
}
