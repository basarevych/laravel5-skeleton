<?php

namespace App\Repositories;

use App\Contracts\Repositories\Users as RepositoryInterface;

use App\User;

class Users implements RepositoryInterface
{
    /**
     * Find the user by his email
     *
     * @param string $email
     * @return User
     */
    public function findByEmail($email)
    {
        $user = User::where('email', $email)
                      ->first();

        return $user;
    }
}
