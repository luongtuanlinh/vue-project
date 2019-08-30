<?php

namespace App\Repositories\Eloquent;

use App\Models\Program;
use App\Repositories\ProgramRepository;

class DBProgramRepository extends DBRepository implements ProgramRepository
{
    public function __construct(Program $model)
    {
        parent::__construct($model);
    }

}