<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\HolidayRequest;
use App\Repositories\HolidayRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HolidayController extends AdminController
{
    public $holidayRepository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }
    public function get(Request $request)
    {
        $list = $this->holidayRepository->allDate($request->all());
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function store(HolidayRequest $request)
    {
        $data = array_merge($request->only('date', 'note', 'repeat'), ['user'=> Auth::id()]);
        $data['date'] = Helper::formatDate($data['date']);
        try {
            $this->holidayRepository->store($data, $request->id);
            return $this->response(true, $request->id? 'cập nhật thành công' : 'thêm mới thành công');
        } catch(\Exception $ex) {
            Log::error('create/update holiday: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, $request->id? 'cập nhật không thành công' : 'cập nhật thành công');
        }
    }

    public function delete(Request $request)
    {
        $item = $this->holidayRepository->findById($request->id);
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
