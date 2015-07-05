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

    /**
     * Create new user
     *
     * @param array $data
     * @param boolean $active
     * @param boolean $admin
     * @return User
     */
    public function create($data, $active = false, $admin = false)
    {
        $user = new User();
        $user->name = isset($data['name']) ? $data['name'] : null;
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->is_active = $active;
        $user->is_admin = $admin;
        $user->save();

        return $user;
    }
}
