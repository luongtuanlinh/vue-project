<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepository;

class DBUserRepository extends DBRepository implements UserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function allUser($role, $cols = ['*'])
    {
        return $this->model->where('role', $role)->get($cols);
    }

    public function userPaginate($role, $per=10)
    {
        return $this->model->where('role', $role)->orderBy('id', 'desc')->paginate($per);
    }
}