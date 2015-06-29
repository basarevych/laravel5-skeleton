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

    /**
     * Create new user
     *
     * @param array $data
     * @param boolean $active
     * @param boolean $admin
     * @return User
     */
    public function create($data, $active = false, $admin = false);
}
