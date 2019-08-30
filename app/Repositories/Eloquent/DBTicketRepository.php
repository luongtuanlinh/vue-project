<?php

namespace App\Repositories\Eloquent;

use App\Helper\Helper;
use App\Models\Holiday;
use App\Models\Item;
use App\Models\ItemBlock;
use App\Models\ItemType;
use App\Models\Options;
use App\Models\Purchase;
use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DBTicketRepository extends DBRepository implements TicketRepository
{
    public $item;
    public $purchase;
    public $itemBlock;
    public $holiday;
    public $itemType;

    public function __construct(Ticket $model, Item $item, Purchase $purchase, ItemBlock $itemBlock, Holiday $holiday, ItemType $itemType)
    {
        $this->item = $item;
        $this->itemBlock = $itemBlock;
        $this->purchase = $purchase;
        $this->holiday = $holiday;
        $this->itemType = $itemType;
        parent::__construct($model);
    }

    //show danh sách vé tập
    public function listTicket($params = [])
    {
        $query = DB::table('tickets as t')
            ->join('items as i', 'i.id', 't.item_id')
            ->join('customers as c', 'c.id', 'customer_id');

        if(isset($params['name'])&&!empty($params['name'])){
            $query = $query->where('c.name', 'like', "%{$params['name']}%");
        }
        if(isset($params['phone'])&&!empty($params['phone'])){
            $query = $query->where('c.phone', 'like', "%{$params['phone']}%");
        }
        if(isset($params['email'])&&!empty($params['email'])){
            $query = $query->where('c.email', 'like', "%{$params['email']}%");
        }
        if(isset($params['date'])&&!empty($params['date'])){
            $query = $query->whereRaw("DATE(start_time)='".Helper::formatDate($params['date'])."'");
        }

        return $query
            ->where('t.status', Ticket::STATUS_COMPLETED)
            ->select(
            't.*',
            'i.name as item',
            'c.name', 'c.email', 'c.phone'
        )->paginate($params['per_page']);
    }

    /**
     * @param $customer_id
     * @param Request $request
     * @return array
     */
    public function storeTickets($customer_id, Request $request)
    {
        $time_type = $request->time_type;
        $price_type = $request->price_type;
        $date = $request->date;
        $ticketsData = $request->selected;
        $dataResponse = [];
        $dateFormat = Helper::formatDate($date);

        $ticketsData = $this->formatDataTicket($ticketsData, $time_type, $price_type, $date);
        foreach($ticketsData as $key => $ticket) {
            //$price = $this->getTicketPrice($ticket['id'], $dateFormat, $ticket['start_time'], $price_type, $time_type);
            $data = [
                'code'  => time()+$key,
                'item_id'   => $ticket['id'],
                'customer_id'   => $customer_id,
                'start_time'    =>  Helper::formatDate($date).' '.$ticket['start_time'],
                'end_time'    =>  Helper::formatDate($date).' '.$ticket['end_time'],
                'price' => $ticket['price'],
                'type'  => $request->time_type,
                'price_type' => $price_type,
                'user_id' => Auth::user()->id,
                'status'    => 1,
                'created_at'    => DB::raw('NOW()'),
                'updated_at'    => DB::raw('NOW()'),
            ];

            $id = $this->store($data);

            $purchase = [
                'code'  => 111,
                'type'  => Purchase::THU_TIEN_VE_TAP,
                'target_id' => $id,
                'cost'  => $ticket['price'],
                'payment_method'    => Purchase::PAYMENT_OFFLINE,
                'user'  => Auth::id()
            ];

            $this->purchase->create($purchase);

            $dataResponse[] = [
                'code'  => '#'.(time()+$key),
                'id'    => $ticket['id'],
                'name'  => $ticket['name'],
                'start_time'    => $date.' '.$ticket['start_time'],
                'end_time'    => $date.' '.$ticket['end_time'],
                'price'     => $ticket['price'],
            ];
        }

        return $dataResponse;
    }

    /**
     * @screen Chọn vé
     * lấy tất cả xe, hiển thị vé xe đã đặt
     * @param array $params
     * @return array
     */
    public function ticketByDateAndItem($params=[]) {
        $items_q = $this->item;
        if (isset($params['item_type']) && !empty($params['item_type'])) {
            $items_q = $items_q->where('type', $params['item_type']);
        }
        $items = $items_q->get(['name', 'id', 'type'])->toArray();

        //vé đã đặt
        $query = DB::table('tickets as t')
            ->join('items as i', 'i.id', 't.item_id')
            ->whereRaw("DATE(t.start_time)='".Helper::formatDate($params['date'])."'")
            ->whereIn('t.status', [Ticket::STATUS_COMPLETED, Ticket::STATUS_PENDING]);

        if (isset($params['item_type']) && !empty($params['item_type'])) {
            $query = $query->where('i.type', $params['item_type']);
        }

        $tickets = $query->select(
            't.item_id', 't.status',
            DB::raw('TIME(t.start_time) as start_time'),
            DB::raw('TIME(t.end_time) as end_time')
        )->get();

        //xe bận
        $blocked_items = $this->itemBlock
            ->whereRaw("DATE(item_block.start_time)='".Helper::formatDate($params['date'])."'")
            ->select(
                'item_block.item_id', 'item_block.type',
                DB::raw('TIME(item_block.start_time) as start_time'),
                DB::raw('TIME(item_block.end_time) as end_time')
            )->get();

        $listItem = array_map(function ($item) use ($tickets, $blocked_items){
            $times = [];
            foreach($tickets as $ticket) {
                if ($item['id']==$ticket->item_id) {
                    $start_time = Carbon::parse($ticket->start_time);
                    $end_time = Carbon::parse($ticket->end_time);
                    $hour = $end_time->diffInHours($start_time);
                    if($hour > 1){
                        for($i = 0; $i < $hour; $i ++){
                            $start_time_current = $start_time;
                            array_push($times, [
                                'start' => $start_time_current->format("H:i:s"),
                                'end'   => $start_time_current->addHour()->format("H:i:s"),
                                'status'=> $ticket->status
                            ]);
                            $start_time = $start_time_current;
                        }

                    }else{
                        $times[] = [
                            'start' => $ticket->start_time,
                            'end'   => $ticket->end_time,
                            'status'=> $ticket->status
                        ];
                    }

                }
            }
            foreach($blocked_items as $blocked_item) {
                if ($item['id']==$blocked_item->item_id) {
                    $times = array_merge($times, $this->timeInterval($blocked_item->start_time, $blocked_item->end_time));
                }
            }
            return array_merge($item, ['times'=>$times]);
        }, $items);
        //dd($listItem);

        return $listItem;
    }

    /**
     * @screen điều phối
     * vé đã đặt
     * @param array $params
     * @return array
     */
    public function getTickets($params=[]) {
        $items_q = $this->item;
        if (isset($params['item_type']) && !empty($params['item_type'])) {
            $items_q = $items_q->where('type', $params['item_type']);
        }
        $items = $items_q->get(['name', 'id'])->toArray();

        //vé đã đặt
        $query = DB::table('tickets as t')
            ->join('items as i', 'i.id', 't.item_id')
            ->leftJoin('users as u', 'u.id', 't.teacher_id')
            ->whereRaw("DATE(t.start_time)='".Helper::formatDate($params['date'])."'");
            //->where('t.type', $params['item_type']);

        if (isset($params['item_type']) && !empty($params['item_type'])) {
            $query = $query->where('i.type', $params['item_type']);
        }

        $tickets = $query->select(
            't.item_id', 't.code', 't.id', 't.teacher_id',
            'u.name as teacher',
            DB::raw('TIME(t.start_time) as start_time'),
            DB::raw('TIME(t.end_time) as end_time')
        )->get();

        $listItem = array_map(function ($item) use ($tickets){
            $times = [];
            foreach($tickets as $ticket) {
                if ($item['id']==$ticket->item_id) {
                    $start_time = Carbon::parse($ticket->start_time);
                    $end_time = Carbon::parse($ticket->end_time);
                    $hour = $end_time->diffInHours($start_time);
                    $time_array = [];
                    if($hour > 1){
                        for($i = 0; $i < $hour; $i ++){
                            array_push($time_array, $start_time->format("H:i:s"));
                            $start_time = $start_time->addHour();
                        }
                    }
                    $times[] = [
                        'id'    => $ticket->id,
                        'start' => $ticket->start_time,
                        'end'   => $ticket->end_time,
                        'code'  => $ticket->code,
                        'hour'  => $hour,
                        'time_array' => $time_array,
                        'teacher_id'    => $ticket->teacher_id,
                        'teacher'   => $ticket->teacher,
                        'status'=> Ticket::STATUS_COMPLETED
                    ];

                }
            }
            return array_merge($item, ['times'=>$times]);
        }, $items);
        //dd($listItem);

        return $listItem;
    }

    /**
     * get ticket's price, check ticket available
     * @param Request $request
     * @return array
     */
    public function checkTickets(Request $request)
    {
        $time_type = $request->time_type;
        $price_type = $request->price_type;
        $date = $request->date;
        $type = $request->time_type;
        $ticketsData = $request->selected;

        $dateFormat = Helper::formatDate($date);

        $tickets = [];
        $total = 0;
        $failure = 0;
        $counter = 0;
        $is_add = 1;
        foreach ($ticketsData as $ticket) {
            $start_time = "{$dateFormat} {$ticket['start_time']}";
            $end_time = "{$dateFormat} {$ticket['end_time']}";
            $isAvailable = $this->isTicketAvailable($ticket['id'], $start_time, $end_time, $type);
            $price = $this->getTicketPrice($ticket['id'], $dateFormat, $ticket['start_time'], $price_type, $time_type);
            if ($isAvailable) {
                $total += $price;
                if($counter >= 1){
                    if(strcmp($tickets[$counter - 1]["end_time"], $date.' '.$ticket['start_time']) == 0){
                        $is_add = 0;
                        $tickets[$counter - 1]["end_time"] = $date.' '.$ticket['end_time'];
                        $tickets[$counter - 1]["price"] += $price;
                    }
                }

            } else {
                $failure++;
            }
            if($is_add){
                $tickets[] = [
                    'id'    => $ticket['id'],
                    'name'  => $ticket['name'],
                    'start_time'    => $date.' '.$ticket['start_time'],
                    'end_time'    => $date.' '.$ticket['end_time'],
                    'price'     => $price,
                    'status'    => $isAvailable
                ];
                $counter = $counter + 1;
            }

            $is_add = 1;

        }

        return [
            'tickets'   => $tickets,
            'total' => $total,
            'failure'   => $failure
        ];
    }


    /**
     * Điều phối giáo viên cho vé tập
     * @param $ticket_id
     * @param $teacher_id
     */
    public function assign($ticket_id, $teacher_id)
    {
        $this->model->where('id', $ticket_id)->update(['teacher_id'=>$teacher_id]);
    }

    /**
     * kiểm tra vé có thẻ đặt đc hay ko
     * @param $item_id
     * @param $start_time
     * @param $type
     * @return int, 1- có thể đặt, 0- ko đc đặt
     */
    private function isTicketAvailable($item_id, $start_time, $end_time, $type)
    {
        $expire_time = 15;
        //check vé đã đặt
        $ticket = $this->model->where('item_id', $item_id)->where('start_time', $start_time)->where('type', $type)
            ->where(function ($query) {
                $query->where('status', Ticket::STATUS_COMPLETED);
            })
            ->orWhere(function ($query) use ($expire_time) {
                $query->where('status', Ticket::STATUS_PENDING)
                    ->whereRaw("created_at > NOW() - INTERVAL {$expire_time} MINUTE");
            })->first();

        if ($ticket) return 0;

        //check xe bị khóa
        $item_type = $this->item->find($item_id)->type;
        $item_blocked = $this->itemBlock
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $end_time)
            ->where(function ($query) use ($item_id, $item_type) {
                    $query->where('item_id', $item_id)
                        ->orWhere('type', $item_type);
            })->first();

        if ($item_blocked) return 0;

        return 1;
    }

    private function getTicketPrice($item_id, $date, $time, $price_type, $time_type)
    {
        $unixTimestamp = strtotime($date);
        //Get the day of the week using PHP's date function.
        $dayOfWeek = date("w", $unixTimestamp);

        $query = $this->item::where('items.id', $item_id)
            ->join('item_type', 'items.type', '=', 'item_type.id')
            ->join('item_type_price', 'item_type_price.item_type_id', '=', 'item_type.id')
            ->where('item_type_price.price_type', $price_type);

        //giá vé cuối tuần
        if (in_array($dayOfWeek, Ticket::WEEKEND)) {
            $time_type = $this->getTimeTypeId($time_type, 0, $price_type);
            $query = $query->where('item_type_price.time_type', $time_type)->first();

            if(empty($query)){
                return 0;
            }
           return $query->price;
        }

        //giá vé ngày nghỉ
        if ($this->isHoliday($date)) {
            $time_type = $this->getTimeTypeId($time_type, 0, $price_type);
            $query = $query->where('item_type_price.time_type', $time_type)->first();

            if(empty($query)){
                return 0;
            }
            return $query->price;
        }

        //giá vé ngoài giờ hành chính
        if (strtotime(Ticket::OFFICE_HOUR)<=strtotime($time)) {
            $time_type = $this->getTimeTypeId($time_type, 0, $price_type);
            $query = $query->where('item_type_price.time_type', $time_type)->first();
            if(empty($query)){
                return 0;
            }

            return $query->price;
        }
        $time_type = $this->getTimeTypeId($time_type, 1, $price_type);
        //giá vé thường
        $query = $query->where('item_type_price.time_type', $time_type)->first();

        if(empty($query)){
            return 0;
        }
        return $query->price;
    }

    private function timeInterval($start, $end)
    {
        $hour = 60*60;
        $times = [];
        $duration = (strtotime($end)-strtotime($start))/$hour;
        for ($i=0; $i<$duration; $i++) {
            $times[] = [
                'start' => date('H:i:s', strtotime($start) + $i*$hour),
                'end'   => date('H:i:s', strtotime($start) + ($i+1)*$hour),
                'status' => Ticket::STATUS_BLOCKED,
            ];
        }

        return $times;
    }

    //kiểm tra ngày nghỉ
    private function isHoliday($date)
    {
        $day = $this->holiday
            ->where(function($query) use ($date) {
                $query->where('date', $date);
            })
            ->orWhere(function ($query) use($date) {
                $query->whereRaw("MONTH(date)='".date('m', strtotime($date))."' and DAY(date)='".date('d', strtotime($date))."'")
                    ->where('repeat', 1);
            })
            ->first();

        if ($day) return true;
        return false;
    }

    /**
     * @param $time_type
     * @param $is_trong_ngay_hanh_chinh
     * @param $price_type
     * @return int
     */
    public function getTimeTypeId($time_type, $is_trong_ngay_hanh_chinh, $price_type){
        if($price_type <= DBItemTypePriceRepository::FOUR_HOUR_TYPE){
            if($is_trong_ngay_hanh_chinh){
                if($time_type == DBItemTypePriceRepository::BUOI_SANG || $time_type == DBItemTypePriceRepository::BUOI_CHIEU){
                    return DBItemTypePriceRepository::TRONG_GIO_HANH_CHINH_TYPE;
                }else{
                    return DBItemTypePriceRepository::NGOAI_GIO_HANH_CHINH_TYPE;
                }
            }else{
                return DBItemTypePriceRepository::NGOAI_GIO_HANH_CHINH_TYPE;
            }
        }else{
            if($is_trong_ngay_hanh_chinh){
                if($time_type == DBItemTypePriceRepository::BUOI_SANG || $time_type == DBItemTypePriceRepository::BUOI_CHIEU){
                    return DBItemTypePriceRepository::TRONG_GIO_HANH_CHINH_TYPE;
                }else{
                    return DBItemTypePriceRepository::BUOI_TOI_TYPE;
                }
            }else{
                if($time_type == DBItemTypePriceRepository::BUOI_SANG || $time_type == DBItemTypePriceRepository::BUOI_CHIEU){
                    return DBItemTypePriceRepository::BUOI_SANG_CHIEU_TYPE;
                }else{
                    return DBItemTypePriceRepository::BUOI_TOI_TYPE;
                }
            }
        }
    }

    public function formatDataTicket($ticketsData, $time_type, $price_type, $date){
        $object = [];
        $counter = 0;
        $dateFormat = Helper::formatDate($date);
        foreach ($ticketsData as $ticket){
            $price = $this->getTicketPrice($ticket['id'], $dateFormat, $ticket['start_time'], $price_type, $time_type);
            $ticket['price'] = $price;
            if($counter == 0){
                array_push($object, $ticket);
                $counter += 1;
            }else{
                //dd($ticket, $object);
                if(strcmp($object[$counter - 1]["end_time"], $ticket["start_time"]) == 0){
                    $object[$counter - 1]["end_time"] = $ticket["end_time"];
                    $object[$counter - 1]["price"] += $ticket["price"];
                }else{
                    array_push($object, $ticket);
                    $counter += 1;
                }
            }
        }
        return $object;
    }

}