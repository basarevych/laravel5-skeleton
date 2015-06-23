<?php

namespace App\Contracts\Repositories;

use App\User;

interface Users
{
    /**
     * Find the user by his email
     *
     * @param string $email
     * @return User
     */
    public function findByEmail($email);
}
