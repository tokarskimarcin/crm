<?php
/**
 * Created by PhpStorm.
 * User: shuwax
 * Date: 21.08.2018
 * Time: 13:50
 */

namespace App\Repository\TestingUser;



use App\User;

class EloquentMyTest implements MyTestRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    function getAll()
    {
        return $this->model->all()->first();
    }

    function getById($id)
    {
        return $this->model->findById($id);
    }
}