<?php

namespace App\Repositories;

use App\Contracts\Repositories\PasswordResets as RepositoryInterface;

use App\User;
use App\PasswordReset;

class PasswordResets implements RepositoryInterface
{
    /**
     * Find the entity by token
     *
     * @param string $token
     * @return PasswordReset
     */
    public function findByToken($token)
    {
        $reset = PasswordReset::where('token', $token)
                                ->first();

        return $reset;
    }

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

    /**
     * Delete expired resets
     *
     * @return PasswordReset
     */
    public function deleteExpired()
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('PT' . (config('auth.password.expire') * 60) . 'S'));
        $resets = PasswordReset::where('updated_at', '<', $date)
                                 ->delete();
    }
}
