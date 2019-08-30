<?php

namespace App\Repositories\Eloquent;

use App\Models\Fee;
use App\Repositories\FeeRepository;
use Illuminate\Support\Facades\DB;

class DBFeeRepository extends DBRepository implements FeeRepository
{
    public function __construct(Fee $model)
    {
        parent::__construct($model);
    }

    public function listFee( $args=[] )
    {
        return DB::table('fees as f')
            ->leftJoin('item_class as c', 'c.id', 'f.class_id')
            ->leftJoin('programs as p', 'p.id', 'f.program_id')
            ->orderBy('c.name')
            ->orderBy('p.name')
            ->select(
                'f.id', 'f.fee', 'p.name as program', 'c.name as class'
            )->get();
    }

    public function checkFeeExist($class, $program)
    {
        return $this->getFee($class, $program);
    }

    public function getFee($class, $program)
    {
        return $this->model->where('class_id', $class)->where('program_id', $program)->first();
    }
}