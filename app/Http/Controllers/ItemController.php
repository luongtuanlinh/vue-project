<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Repositories\Eloquent\DBItemTypeRepository;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemController extends AdminController
{
    public $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function all()
    {
        return $this->itemRepository->allItemWithType();
    }

    public function list(Request $request)
    {
        $list = $this->itemRepository->getItems($request);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        foreach ($data['data'] as $item){
            $item->type_name = $item->item_type->name;
            $item->type_class_name = DBItemTypeRepository::getTextFromType($item->item_type->type);
        }
        return $this->response(true, "", $data);
    }

    public function detail(Request $request) {
        $id = $request->id;
        $item = $this->itemRepository->findById($id, ['name', 'type']);
        return $this->response(true, '', $item);
    }

    public function store(ItemRequest $request)
    {
        $data = $request->only('name', 'type');
        try {
            $this->itemRepository->store($data);
            return $this->response(true, 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('Create new item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function update(ItemRequest $request, $id)
    {
        $data = $request->only('name', 'phone');
        try {
            $this->itemRepository->store($data, $id);
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch(\Exception $ex) {
            Log::error('update item: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->itemRepository->findById($request->id);
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
}
