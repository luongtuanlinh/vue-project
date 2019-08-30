<?php

namespace App\Http\Controllers;

use App\Exports\ExamStudentExport;
use App\Helper\Helper;
use App\Http\Requests\ExamRequest;
use App\Repositories\ExamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends AdminController
{
    public $examRepository;
    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function list(Request $request)
    {
        $list = $this->examRepository->examPaginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function store(ExamRequest $request)
    {
        $data = $request->only('name', 'date', 'type', 'code', 'class_id', 'fee');
        $data['date'] = Helper::formatDate($data['date']);
        try {
            $this->examRepository->store($data);
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('Create new exam: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request, $id)
    {
        $exam = $this->examRepository->findById($id)->toArray();
        $exam['date'] = Helper::parseDate($exam['date']);
        return $this->response(true, '', $exam);
    }

    public function update(ExamRequest $request, $id)
    {
        $data = $request->only('name', 'date', 'class_id', 'fee');
        $data['date'] = Helper::formatDate($data['date']);
        try {
            $this->examRepository->store($data, $id);
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            Log::error('update course: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $exam = $this->examRepository->findById($request->id);
        if ( !$exam ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $exam->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove course: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }

    //thêm học viên vào lớp thi
    public function addStudent(Request $request)
    {
        $exam = $this->examRepository->findById($request->id);
        try{
            $exam->students()->attach($request->students);
            return $this->successResponse("thêm học viên vào danh sách thi thành công");
        } catch(\Exception $ex) {
            Log::error('Exam add students: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->errorResponse("thêm học viên vào danh sách thi không thành công");
        }
    }

    //get danh sách học viên lớp thi
    public function getStudents(Request $request, $id)
    {
        $students = $this->examRepository->getStudents($id, $request->all());
        $data = [
            'data'  => $students->items(),
            'total' => $students->total()
        ];
        return $this->successResponse('', $data);
    }

    //xóa hv khỏi ds lớp thi
    public function removeStudent(Request $request, $id)
    {
        $exam = $this->examRepository->findById($id);
        $student = $exam->students()->wherePivot('student_id', $request->studentID)->first();

        if ($student->pivot->fee==1) {
            return $this->errorResponse("Không thể xóa học viên đã nộp lệ phí thi");
        }
        try{
            $exam->students()->detach($request->studentID);
            return $this->successResponse("xóa học viên khỏi danh sách thi");
        } catch(\Exception $ex) {
            Log::error('Exam remove student: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->errorResponse("xóa không thành công");
        }
    }

    //action=1 cap nhat trang thái đóng tiền thi, action=2 xóa hv khỏi lớp thi
    public function updateBulkStudent(Request $request, $id)
    {
        $exam = $this->examRepository->findById($id);

        if ($request->action==2) {
            $students_paid_count = $exam->students()->newPivotStatement()->whereIn('student_id', $request->student_ids)->where('fee', 1)->count();
            if ($students_paid_count>0) {
                return $this->errorResponse("Không thể xóa học viên đã đóng lệ phí. vui lòng kiểm tra lại");
            }
        }
        DB::beginTransaction();
        try {
            if ($request->action==1) {
                $exam->students()->newPivotStatement()->whereIn('student_id', $request->student_ids)->update(['fee'=>1]);
            }
            if ($request->action==2) {
                $exam->students()->detach($request->student_ids);
            }
            DB::commit();
            return $this->successResponse( 'cập nhật thành công');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('updateBulkStudent: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->errorResponse('cập nhật không thành công');
        }
    }

    public function export($id)
    {
        $students = $this->examRepository->allStudent($id)->toArray();
        return Excel::download(new ExamStudentExport($students), 'export.xlsx');
    }
}
