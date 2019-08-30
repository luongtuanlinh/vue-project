<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Models\User;
use App\Repositories\CourseRepository;
use App\Repositories\FeeRepository;
use App\Repositories\ProgramRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeeController extends AdminController
{
    public $feeRepository;
    public $courseRepository;
    public $programRepository;
    public $userRepository;

    public function __construct(FeeRepository $feeRepository, UserRepository $userRepository,
                                CourseRepository $courseRepository,
                                ProgramRepository $programRepository)
    {
        $this->feeRepository = $feeRepository;
        $this->courseRepository = $courseRepository;
        $this->programRepository = $programRepository;
        $this->userRepository = $userRepository;
    }

    public function list(Request $request)
    {
        $data = $this->feeRepository->listFee($request->all());
        return $this->response(true, "", $data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $item = $this->feeRepository->findById($id);
        return $this->response(true, '', $item);
    }

    public function store(FeeRequest $request)
    {
        $feeExist = $this->feeRepository->checkFeeExist($request->class_id, $request->program_id);
        if ($feeExist) {
            return $this->response(false, 'loại phí đã tồn tại');
        }
        $data = $request->only('class_id', 'program_id', 'fee');
        try {
            $this->feeRepository->store($data);
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('Create new item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only('fee');
        try {
            $this->feeRepository->store($data, $id);
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            Log::error('update item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->feeRepository->findById($request->id);
        if ( !$item ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $item->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }

    public function getFee(Request $request)
    {
        $course = $this->courseRepository->findById($request->course_id);
        $class_id = $course->car_class_id;
        $program_id = $request->program_id;

        $fee = $this->feeRepository->getFee($class_id, $program_id);
        if ( !$fee ) {
            return $this->errorResponse('không có thông tin học phí khóa học');
        }

        return $this->successResponse('', ['fee'=>$fee->fee]);

    }
}
