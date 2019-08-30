<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemClassRequest;
use App\Repositories\ItemClassRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemClassController extends AdminController
{
    public $itemClassRepository;

    public function __construct(ItemClassRepository $itemClassRepository)
    {
        $this->itemClassRepository = $itemClassRepository;
    }

    public function list(Request $request)
    {
        $list = $this->itemClassRepository->paginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function store(ItemClassRequest $request)
    {
        $data = $request->only('name', 'note');
        try {
            $this->itemClassRepository->store($data);
            return $this->response(true, 'thêm mới dữ liệu thành công');
        } catch (\Exception $ex) {
            Log::error('ItemClass create: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $itemClass = $this->itemClassRepository->findById($request->id, ['name', 'note']);
        return $this->response(true, '', $itemClass);
    }

    public function update(ItemClassRequest $request, $id)
    {
        $data = $request->only('name', 'note');
        try {
            $this->itemClassRepository->store($data, $id);
            return $this->response(true, 'cập nhật thành công');
        } catch (\Exception $ex) {
            Log::error('ItemClass update: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->itemClassRepository->findById($request->id);
        if ( !$item ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $item->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('ItemClass remove: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }

    public function all()
    {
        $all = $this->itemClassRepository->all(['name as text', 'id']);
        return $this->response(true, '', $all);
    }

}
