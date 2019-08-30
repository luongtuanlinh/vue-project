<?php

namespace App\Repositories\Eloquent;

use App\Models\Source;
use App\Repositories\SourceRepository;

class DBSourceRepository extends DBRepository implements SourceRepository
{
    public function __construct(Source $model)
    {
        parent::__construct($model);
    }

}