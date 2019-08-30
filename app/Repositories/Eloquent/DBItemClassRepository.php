<?php

namespace App\Repositories\Eloquent;

use App\Models\ItemClass;
use App\Repositories\ItemClassRepository;

class DBItemClassRepository extends DBRepository implements ItemClassRepository
{
    public function __construct(ItemClass $model)
    {
        parent::__construct($model);
    }

}