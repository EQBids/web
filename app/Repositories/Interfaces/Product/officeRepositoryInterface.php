<?php

namespace App\Repositories\Interfaces\Product;

use App\Repositories\Interfaces\baseRepositoryInterface;
use App\User;

interface officeRepositoryInterface extends baseRepositoryInterface {

    public function createAndAddUser(array $data,$userId);

    public function offices(User $user);
}