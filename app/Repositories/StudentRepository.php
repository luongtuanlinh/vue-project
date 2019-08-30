<?php

namespace App\Repositories;

interface StudentRepository
{
    public function checkExistInCourse(array $params);
    public function getInfo($id);
}
