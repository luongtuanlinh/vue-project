<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = ['code', 'item_id', 'customer_id', 'price_type', 'start_time', 'end_time', 'type', 'price', 'status', 'teacher_id'];

    CONST TYPE_LINE = 1;
    CONST TYPE_CIRCLE = 2;
    CONST STATUS_PENDING = 0;
    CONST STATUS_COMPLETED = 1;
    CONST STATUS_BLOCKED = 99;

    CONST WEEKEND = [0, 6];         //chủ nhật, thứ 7
    CONST OFFICE_HOUR = '16:30:00';
}
