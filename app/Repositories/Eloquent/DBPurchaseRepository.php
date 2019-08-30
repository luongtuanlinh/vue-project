<?php

namespace App\Repositories\Eloquent;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;

class DBPurchaseRepository extends DBRepository implements PurchaseRepository
{
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

}
