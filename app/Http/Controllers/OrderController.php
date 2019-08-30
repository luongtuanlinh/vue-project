<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\OrderRequest;
use App\Repositories\CourseRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends AdminController
{
    public $orderRepository;
    public $courseRepository;

    public function __construct(OrderRepository $orderRepository, CourseRepository $courseRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->courseRepository = $courseRepository;
    }

    public function list(Request $request)
    {
        $list = $this->orderRepository->getOrders($request);
        $data = [
            'data'  => $list->items(),
            'total' => $list->total()
        ];
        return $this->response(true, "", $data);
    }

    public function detail(Request $request)
    {
        $order = $this->orderRepository->findById($request->id)->toArray();
        $order['time_order'] = Helper::parseDate($order['time_order']);
        $order['expire_date'] = Helper::parseDate($order['expire_date']);
        return $this->response(true, "", $order);
    }

    public function info($id)
    {
        $order = $this->orderRepository->info($id);
        $order = array_merge($order->toArray(), [
           'name_user'   => $order->user->name,
            'name_course'    => $order->course->name,
            'time_order'    => Helper::parseDate($order['time_order']),
            'expire_date'    => Helper::parseDate($order['expire_date']),
        ]);

        unset($order['user']);
        unset($order['course']);

        return $this->response(true, "", $order);
    }

    public function store(OrderRequest $request)
    {
        $data = $request->all();
        $course = $this->courseRepository->findById($data['course_id'], ['quantity', 'max']);
        if ($course->max - $course->quantity < $data['quantity']) {
            return $this->response(false, 'Vượt quá sỹ số tối đa của khóa học.');
        }
        $data['time_order'] = Helper::formatDate($data['time_order']);
        $data['expire_date'] = Helper::formatDate($data['expire_date']);
        $data['admin_id'] = Auth::id();
        DB::beginTransaction();
        try {
            $this->orderRepository->store($data);
            DB::commit();
            return $this->response(true, 'tạo mới dữ liệu thành công');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('create teacher order: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'tạo mới dữ liệu không thành công');
        }
    }

    public function update(OrderRequest $request, $id)
    {
        $data = $request->all();
        $order = $this->orderRepository->findById($id, ['quantity']);
        $course = $this->courseRepository->findById($data['course_id'], ['quantity', 'max']);
        if ($course->max - $course->quantity < $data['quantity'] - $order->quantity) {
            return $this->response(false, 'Vượt quá sỹ số tối đa của khóa học.');
        }
        $data['time_order'] = Helper::formatDate($data['time_order']);
        $data['expire_date'] = Helper::formatDate($data['expire_date']);
        DB::beginTransaction();
        try {
            $this->orderRepository->store($data, $id);
            DB::commit();
            return $this->response(true, 'cập nhật dữ liệu thành công');
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('update teacher order: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'cập nhật dữ liệu không thành công');
        }
    }

    public function delete(Request $request)
    {
        $teacher = $this->orderRepository->findById($request->id);

        if ( !$teacher ) {
            return $this->response(false, "dữ liệu không tồn tại hoặc đã bị xóa");
        }
        DB::beginTransaction();
        try{
            $this->orderRepository->delete($request->id);
            DB::commit();
            return $this->response(true, "xóa dữ liệu thành công");
        } catch(\Exception $ex) {
            Log::error('remove teacher: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, "xóa dữ liệu không thành công");
        }
    }
}
