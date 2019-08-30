<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type'];
    protected $table = "items";

    public function item_type()
    {
        return $this->belongsTo('App\Models\ItemType', "type");
    }
}
