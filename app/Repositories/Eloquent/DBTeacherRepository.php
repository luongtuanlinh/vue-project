<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\TeacherRepository;

class DBTeacherRepository extends DBRepository implements TeacherRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

}