<?php

namespace App\Contracts\Repositories;

use App\User;
use App\PasswordReset;

interface PasswordResets
{
    /**
     * Find the entity by token
     *
     * @param string $token
     * @return PasswordReset
     */
    public function findByToken($token);

    /**
     * Create new password reset
     *
     * @param User $user
     * @return PasswordReset
     */
    public function create(User $user);
}
