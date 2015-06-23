<?php

namespace App\Repositories;

use App\Contracts\Repositories\PasswordResets as RepositoryInterface;

use App\User;
use App\PasswordReset;

class PasswordResets implements RepositoryInterface
{
    /**
     * Create new password reset
     *
     * @param User $user
     * @return PasswordReset
     */
    public function create(User $user)
    {
        $reset = new PasswordReset();
        $reset->token = PasswordReset::generateToken();

        $user->passwordResets()->save($reset);

        return $reset;
    }
}
