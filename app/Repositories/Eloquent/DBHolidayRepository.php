<?php

namespace App\Repositories\Eloquent;

use App\Models\Holiday;
use App\Repositories\HolidayRepository;

class DBHolidayRepository extends DBRepository implements HolidayRepository
{
    public function __construct(Holiday $model)
    {
        parent::__construct($model);
    }

    public function allDate($params=[])
    {
        return $this->model->join('users as u', 'u.id', 'holidays.user')
            ->select(
                'u.name', 'holidays.*'
            )->paginate($params['per_page']);
    }
}