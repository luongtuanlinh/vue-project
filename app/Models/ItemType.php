<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $table = 'item_type';

    protected $fillable = ['name', 'note', 'type'];
}
