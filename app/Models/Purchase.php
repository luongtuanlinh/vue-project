<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $fillable = ['code', 'type', 'target_id', 'cost', 'payment_method', 'user'];

    CONST THU_TIEN_VE_TAP = 1;
    CONST THU_TIEN_HOC_PHI = 2;

    CONST PAYMENT_OFFLINE = 1;
    CONST PAYMENT_ONLINE = 2;

}
