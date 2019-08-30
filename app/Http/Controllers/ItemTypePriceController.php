<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemTypePriceRequest;
use App\Models\ItemTypePrice;
use App\Repositories\Eloquent\DBItemTypePriceRepository;
use App\Repositories\Eloquent\DBItemTypeRepository;
use App\Repositories\ItemTypePriceRepository;
use Illuminate\Http\Request;
use Log;

class ItemTypePriceController extends AdminController
{
    public $itemTypePriceRepository;

    public function __construct(ItemTypePriceRepository $itemTypePriceRepository)
    {
        $this->itemTypePriceRepository = $itemTypePriceRepository;
    }

    public function list(Request $request)
    {
        $list = $this->itemTypePriceRepository->paginate($request->per_page);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        foreach ($data['data'] as $key => $value){
            $data['data'][$key]->price_type = $this->itemTypePriceRepository::getTextFromPriceType($value->price_type);
            $data['data'][$key]->time_type = $this->itemTypePriceRepository::getTextFromTimeType($value->time_type);
            $data['data'][$key]->item_type_id = $value->item_type->name . " - " . DBItemTypeRepository::getTextFromType($value->item_type->type);
        }

        return $this->response(true, "", $data);
    }

    public function store(ItemTypePriceRequest $request)
    {
        $data = $request->only('item_type_id', 'time_type', 'price_type', 'price');
        try {
            $object = ItemTypePrice::where([
                "item_type_id" => $data["item_type_id"],
                "time_type" => $data["time_type"],
                "price_type" => $data["price_type"]
            ])->first();
            if(!empty($object)){
                return $this->response(false, 'Bản ghi đã tồn tại');
            }
            $this->itemTypePriceRepository->store($data);
            return $this->response(true, 'thêm mới dữ liệu thành công');
        } catch (\Exception $ex) {
            Log::error('ItemTypePrice create: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'thêm mới không thành công');
        }
    }

    public function detail(Request $request)
    {
        $itemClass = $this->itemTypePriceRepository->findById($request->id, ['item_type_id', 'time_type', 'price_type', 'price']);
        return $this->response(true, '', $itemClass);
    }

    public function update(ItemTypePriceRequest $request)
    {
        $data = $request->only('item_type_id', 'time_type', 'price_type', 'price');
        try {
            $object = ItemTypePrice::where([
                "item_type_id" => $data["item_type_id"],
                "time_type" => $data["time_type"],
                "price_type" => $data["price_type"]
            ])->first();
            if(!empty($object)){
                return $this->response(false, 'Bản ghi đã tồn tại');
            }
            $this->itemTypePriceRepository->store($data, $request->id);
            return $this->response(true, 'cập nhật thành công');
        } catch (\Exception $ex) {
            Log::error('ItemType update: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->itemTypePriceRepository->findById($request->id);
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
        $all = $this->itemTypePriceRepository->all(['name as text', 'id']);
        return $this->response(true, '', $all);
    }

    public function listType(){
        $types = $this->itemTypePriceRepository::listObjectType();
        return $this->response(true, '', $types);
    }
}
