<?php

namespace App\Services;

use App\Base\BaseModel;
use App\Models\User;

class UserService
{
    private $user_model;
    public function __construct(User $user)
    {
        $this->user_model = new BaseModel($user);
    }

    public function index()
    {
        $this->user_model->all();
    }
}