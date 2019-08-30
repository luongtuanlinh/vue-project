<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Repositories\CustomerRepository;
use App\Repositories\Eloquent\DBItemTypePriceRepository;
use App\Repositories\Eloquent\DBItemTypeRepository;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends AdminController
{
    public $ticketRepository;
    public $customerRepository;

    public function __construct(TicketRepository $ticketRepository, CustomerRepository $customerRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->customerRepository = $customerRepository;
    }

    public function list(Request $request)
    {
        $list = $this->ticketRepository->listTicket($request->all());
        foreach ($list as $item){
            $item->price_type = DBItemTypePriceRepository::getTextFromPriceType($item->price_type);
        }
        return $this->response(true, '', $list);
    }

    public function get(Request $request)
    {
        $tickets = $this->ticketRepository->ticketByDateAndItem($request->all());
        return $this->response(true, '', $tickets);
    }

    public function getTickets(Request $request)
    {
        $tickets = $this->ticketRepository->getTickets($request->all());
        return $this->response(true, '', $tickets);
    }

    public function create(Request $request)
    {
        $tickets = $this->ticketRepository->checkTickets($request);
        return $this->response(true, '', $tickets);
    }

    public function store(TicketRequest $request)
    {
        $customerData = $request->only('name', 'phone', 'email');
        $ticketsChecked = $this->ticketRepository->checkTickets($request);
        if ($ticketsChecked['failure']>0) {
            return $this->response(false, 'có vé đã bị mua', $ticketsChecked);
        }
        DB::beginTransaction();
        try {
            $customer_id = $this->customerRepository->store($customerData);
            $tickets = $this->ticketRepository->storeTickets($customer_id, $request);
            DB::commit();
            return $this->response(true, 'Đặt vé thành công', $tickets);
        } catch(\Exception $ex) {
            DB::rollBack();
            Log::error('Create ticket: fail. message: '.$ex->getMessage().'. file: '.$ex->getFile().'. line: '.$ex->getLine());
            return $this->response(false, 'Đặt vé không thành công');
        }
    }

    public function assign(Request $request)
    {
        try {
            $this->ticketRepository->assign($request->id, $request->teacher_id);
            return $this->response(true, 'Điều phối giáo viên thành công');
        } catch (\Exception $ex) {
            Log::error('assign teacher: fail. message: ' . $ex->getMessage() . '. file: ' . $ex->getFile() . '. line: ' . $ex->getLine());
            return $this->response(false, 'Điều phối giáo viên không thành công');
        }
    }
}
