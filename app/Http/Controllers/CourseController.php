<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\CourseRequest;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends AdminController
{
    public $courseRepository;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }


    public function all(Request $request) {
        $all = $this->courseRepository->allCourse($request->course);
        return $this->response(true, '', $all);
    }

    public function list(Request $request)
    {
        $list = $this->courseRepository->listCourse($request);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }


    public function store(CourseRequest $request)
    {
        $data = $request->all();
        $data = array_merge($data, [
            'start_time' => Helper::formatDate($data['start_time']),
            'end_time' => Helper::formatDate($data['end_time']),
            'exam_time' => Helper::formatDate($data['exam_time']),
            'exam_end_time' => Helper::formatDate($data['exam_end_time'])
        ]);
        try {
            $this->courseRepository->store($data);
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('Create new item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $course = $this->courseRepository->findById($id)->toArray();
        $course['start_time'] = Helper::parseDate($course['start_time']);
        $course['end_time'] = Helper::parseDate($course['end_time']);
        $course['exam_time'] = Helper::parseDate($course['exam_time']);
        $course['exam_end_time'] = Helper::parseDate($course['exam_end_time']);
        return $this->response(true, '', $course);
    }

    public function update(CourseRequest $request, $id)
    {
        $data = $request->all();
        $data = array_merge($data, [
           'start_time' => Helper::formatDate($data['start_time']),
           'end_time' => Helper::formatDate($data['end_time']),
           'exam_time' => Helper::formatDate($data['exam_time']),
           'exam_end_time' => Helper::formatDate($data['exam_end_time'])
        ]);
        try {
            $this->courseRepository->store($data, $id);
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            Log::error('update course: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $course = $this->courseRepository->findById($request->id);
        if ( !$course ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $course->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove course: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }
}
