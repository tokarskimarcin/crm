<?php
/**
 * Created by PhpStorm.
 * User: shuwax
 * Date: 21.08.2018
 * Time: 13:50
 */

namespace App\Repository\TestingUser;
use App\Department_info;

class EloquentMyTestDepartment implements MyTestDepartmentRepository
{
    private $model;

    public function __construct(Department_info $model)
    {
        $this->model = $model;
    }

    function getAll()
    {
        return $this->model->all();
    }

    function getById($id)
    {
        return $this->model->findById($id);
    }
}