<?php

namespace App\Contracts\Repositories;

use App\User;
use App\PasswordReset;

interface PasswordResets
{
    /**
     * Create new password reset
     *
     * @param User $user
     * @return PasswordReset
     */
    public function create(User $user);
}
