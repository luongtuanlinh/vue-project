<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTypePrice extends Model
{
    protected $table = 'item_type_price';

    protected $fillable = ['item_type_id', 'price_type', 'time_type', 'price'];

    public function item_type()
    {
        return $this->belongsTo('App\Models\ItemType', 'item_type_id')->whereNull('deleted_at');
    }
}
