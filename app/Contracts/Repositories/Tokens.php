<?php

namespace App\Contracts\Repositories;

use App\User;
use App\Token;

interface Tokens
{
    /**
     * Find the entity by token
     *
     * @param string $token
     * @return Token
     */
    public function findByToken($token);

    /**
     * Create new token
     *
     * @param User $user
     * @param string $type
     * @return Token
     */
    public function create(User $user, $type);

    /**
     * Delete expired tokens
     *
     * @return Token
     */
    public function deleteExpired();
}
