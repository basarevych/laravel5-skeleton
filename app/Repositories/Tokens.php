<?php

namespace App\Repositories;

use App\Contracts\Repositories\Tokens as RepositoryInterface;

use App\User;
use App\Token;

class Tokens implements RepositoryInterface
{
    /**
     * Find the entity by token
     *
     * @param string $token
     * @return Token
     */
    public function findByToken($token)
    {
        $token = Token::where('token', $token)
                      ->first();

        return $token;
    }

    /**
     * Create new token
     *
     * @param User $user
     * @param string $type
     * @return Token
     */
    public function create(User $user, $type)
    {
        $token = new Token();
        $token->type = $type;
        $token->token = Token::generateToken();

        $user->tokens()->save($token);

        return $token;
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
        Token::where('type', Token::TYPE_PASSWORD_RESET)
             ->where('updated_at', '<', $date)
             ->delete();

        $date = new \DateTime();
        $date->sub(new \DateInterval('PT' . (config('auth.registration.expire') * 60) . 'S'));
        Token::where('type', Token::TYPE_REGISTRATION)
             ->where('updated_at', '<', $date)
             ->delete();
    }
}
