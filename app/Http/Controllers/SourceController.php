<?php

namespace App\Http\Controllers;

use App\Http\Requests\SourceRequest;
use App\Repositories\SourceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SourceController extends AdminController
{
    public $sourceRepository;

    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function list(Request $request)
    {
        $list = $this->sourceRepository->paginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function store(SourceRequest $request)
    {
        $data = $request->only('name', 'description');
        try {
            $this->sourceRepository->store($data);
            return $this->response(true, 'thêm mới dữ liệu thành công');
        } catch (\Exception $ex) {
            Log::error('Source create: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $itemClass = $this->sourceRepository->findById($request->id, ['name', 'description']);
        return $this->response(true, '', $itemClass);
    }

    public function update(SourceRequest $request, $id)
    {
        $data = $request->only('name', 'description');
        try {
            $this->sourceRepository->store($data, $id);
            return $this->response(true, 'cập nhật thành công');
        } catch (\Exception $ex) {
            Log::error('Source update: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $program = $this->sourceRepository->findById($request->id);
        if ( !$program ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $program->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('Source remove: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }

    public function all()
    {
        $all = $this->sourceRepository->all(['name as text', 'id']);
        return $this->response(true, '', $all);
    }

}
