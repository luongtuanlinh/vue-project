<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemBlockRequest;
use App\Repositories\ItemBlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemBlockController extends AdminController
{
    public $itemBlockRepository;

    public function __construct(ItemBlockRepository $itemBlockRepository)
    {
        $this->itemBlockRepository = $itemBlockRepository;
    }
    public function list(Request $request)
    {
        $list = $this->itemBlockRepository->getItems($request->all());
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $item = $this->itemRepository->findById($id, ['name', 'type']);
        return $this->response(true, '', $item);
    }

    public function store(ItemBlockRequest $request)
    {
        try {
            $this->itemBlockRepository->storeItemBlock($request);
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('Create new item block: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->itemBlockRepository->findById($request->id);
        if ( !$item ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        try{
            $item->delete();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove item block: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }
}
