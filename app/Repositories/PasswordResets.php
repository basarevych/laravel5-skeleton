<?php

namespace App\Repositories;

use App\Contracts\Repositories\PasswordResets as RepositoryInterface;

class PasswordResets implements RepositoryInterface
{
    public function create(\App\User $user)
    {
        $reset = new \App\PasswordReset();
        $reset->token = \App\PasswordReset::generateToken();

        $user->passwordResets()->save($reset);

        return $reset;
    }
}
