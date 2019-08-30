<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends AdminController
{
    public $option;

    public function __construct(Options $option)
    {
        $this->option = $option;
    }

    public function get()
    {
        $options = $this->option->getAll();
        return $this->response(true, '', $options);
    }

    public function store(SettingRequest $request)
    {
        $options = $request->all();
        DB::beginTransaction();
        try {
            foreach ($options as $key => $value) {
                $this->option->set($key, $value);
            }
            DB::commit();
            return $this->response(true, 'cập nhật thành công');
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('update settings: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'cập nhật không thành công');
        }
    }
}
