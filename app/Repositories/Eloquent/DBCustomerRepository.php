<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\CustomerRepository;

class DBCustomerRepository extends DBRepository implements CustomerRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }



}