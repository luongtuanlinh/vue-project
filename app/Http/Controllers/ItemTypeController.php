<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemTypeRequest;
use App\Repositories\ItemTypeRepository;
use Illuminate\Http\Request;
use Log;

class ItemTypeController extends AdminController
{
    public $itemTypeRepository;

    public function __construct(ItemTypeRepository $itemTypeRepository)
    {
        $this->itemTypeRepository = $itemTypeRepository;
    }

    public function list(Request $request)
    {
        $list = $this->itemTypeRepository->paginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        foreach ($data['data'] as $key => $value){
            $data['data'][$key]->type = $this->itemTypeRepository::getTextFromType($value->type);
        }

        return $this->response(true, "", $data);
    }

    public function store(ItemTypeRequest $request)
    {
        $data = $request->only('name', 'note', 'type');
        try {
            $this->itemTypeRepository->store($data);
            return $this->response(true, 'thêm mới dữ liệu thành công');
        } catch (\Exception $ex) {
            Log::error('ItemType create: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $itemClass = $this->itemTypeRepository->findById($request->id, ['name', 'note', 'type']);
        return $this->response(true, '', $itemClass);
    }

    public function update(ItemTypeRequest $request)
    {
        $data = $request->only('name', 'note', 'type');
        try {
            $this->itemTypeRepository->store($data, $request->id);
            return $this->response(true, 'cập nhật thành công');
        } catch (\Exception $ex) {
            Log::error('ItemType update: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->itemTypeRepository->findById($request->id);
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
        $all = $this->itemTypeRepository->all(['name as text', 'type', 'id']);
        foreach ($all as $item){
            $item->text = $item->text . " - " . $this->itemTypeRepository::getTextFromType($item->type);
        }
        return $this->response(true, '', $all);
    }

    public function listType(){
        $types = $this->itemTypeRepository::listObjectType();
        return $this->response(true, '', $types);
    }

}
