<?php

namespace App\Http\Controllers;

use App\Repository\TestingUser\MyTestRepository;

class MyUserTestController extends Controller
{
    private $user;

    public function __construct(MyTestRepository $user)
    {
        $this->user = $user;
    }

    public function getAllTask(){

        return $this->user->getAll();
    }
}
