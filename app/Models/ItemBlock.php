<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemBlock extends Model
{
    use SoftDeletes;
    protected $table = 'item_block';

    protected $fillable = ['type', 'item_id', 'start_time', 'end_time', 'user', 'note'];
}
