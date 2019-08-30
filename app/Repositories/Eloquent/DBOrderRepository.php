<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class DBOrderRepository extends DBRepository implements OrderRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getOrders($request)
    {
        return DB::table('orders as o')
            ->join('users as u', 'u.id', 'o.user_id')
            ->join('courses as c', 'c.id', 'o.course_id')
            ->select(
                'o.quantity', 'o.id', 'o.time_order', 'o.expire_date', 'o.paid', 'o.total_fee', 'o.applied',
                'u.name as user', 'u.role as type',
                'c.name as course'
            )
            ->paginate($request->per_page);
    }

    public function store(array $params, $id = null)
    {
        if ( !$id ) {
            $this->create($params);
            DB::table('courses')
                ->where('id', $params['course_id'])
                ->increment('quantity', $params['quantity']);
        } else {
            $order = $this->findById($id);

            if ($order->course_id != $params['course_id']) {        //thay đôi khóa học
                DB::table('courses')
                    ->where('id', $order->course_id)
                    ->decrement('quantity', $order->quantity);
                DB::table('courses')
                    ->where('id', $params['course_id'])
                    ->increment('quantity', $params['quantity']);
            } else {
                $qtt =  $params['quantity'] - $order->quantity;
                DB::table('courses')
                    ->where('id', $order->course_id)
                    ->increment('quantity', $qtt);
            }

            $this->update($params, $id);
        }
    }

    public function delete($id)
    {
        $order = $this->findById($id);
        DB::table('courses')
            ->where('id', $order->course_id)
            ->decrement('quantity', $order->quantity);
        $order->delete();
    }

    public function info($id)
    {
        return $this->model->where('id', $id)->with('user', 'course')->first();
    }

    public function updateQuantity($qtt=1)
    {
        DB::table('orders')->increment('applied', $qtt);
    }

}