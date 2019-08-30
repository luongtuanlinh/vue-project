<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramRequest;
use App\Repositories\ProgramRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramController extends AdminController
{
    public $programRepository;

    public function __construct(ProgramRepository $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function list(Request $request)
    {
        $list = $this->programRepository->paginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function store(ProgramRequest $request)
    {
        $data = $request->only('name', 'description', 'fee', 'time');
        try {
            $this->programRepository->store($data);
            return $this->response(true, 'thêm mới dữ liệu thành công');
        } catch (\Exception $ex) {
            Log::error('Program create: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $itemClass = $this->programRepository->findById($request->id, ['name', 'description', 'fee', 'time']);
        return $this->response(true, '', $itemClass);
    }

    public function getByName($name)
    {
        $program = $this->programRepository->findBy('name', $name);
        return $this->response(true, '', $program);
    }

    public function update(ProgramRequest $request, $id)
    {
        $data = $request->only('name', 'description', 'fee', 'time');
        try {
            $this->programRepository->store($data, $id);
            return $this->response(true, 'cập nhật thành công');
        } catch (\Exception $ex) {
            Log::error('Program update: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $program = $this->programRepository->findById($request->id);
        if ( !$program ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $program->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('Program remove: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }

    public function all()
    {
        $all = $this->programRepository->all(['name as text', 'fee', 'id']);
        return $this->response(true, '', $all);
    }

}
