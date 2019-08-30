<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemClass extends Model
{
    protected $table = 'item_class';

    protected $fillable = ['name', 'note'];
}
