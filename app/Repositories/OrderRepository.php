<?php

namespace App\Repositories;

interface OrderRepository
{
    public function store(array $params, $id=null);
}