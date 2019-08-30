<?php

namespace App\Repositories\Eloquent;

use App\Helper\Helper;
use App\Models\ItemBlock;
use App\Repositories\ItemBlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DBItemBlockRepository extends DBRepository implements ItemBlockRepository
{
    public function __construct(ItemBlock $model)
    {
        parent::__construct($model);
    }

    public function storeItemBlock(Request $request)
    {
        $date = Helper::formatDate($request->date);
        $start_time = "{$date} {$request->start_time}:00";
        $end_time = "{$date} {$request->end_time}:00";
        $item_ids = $request->item_id;

        if (!$item_ids) {
            $data = [
                'type'  => $request->type,
                'start_time' => $start_time,
                'end_time'  => $end_time,
                'user'  => Auth::id(),
                'note'  => $request->note
            ];
            $this->store($data);
        } else {
            $data = [];
            foreach($item_ids as $item_id) {
                $data[] = [
                    'type'  => $request->type,
                    'item_id'   => $item_id,
                    'start_time'=> $start_time,
                    'end_time'  => $end_time,
                    'user'  => Auth::id(),
                    'note'  => $request->note,
                    'created_at'    => DB::raw('NOW()'),
                    'updated_at'    => DB::raw('NOW()')
                ];
            }
            $this->model->insert($data);
        }

    }

    public function getItems($params=[])
    {
        $query = $this->model
            ->leftJoin('items as i', 'i.id', 'item_block.item_id')
            ->leftJoin('item_type as c', 'c.id', 'item_block.type')
            ->join('users as u', 'u.id', 'item_block.user');
        return $query->select(
            'item_block.*', 'i.name as item', 'c.name as type', 'u.name as user'
            )
            ->paginate($params['per_page']);

    }

}