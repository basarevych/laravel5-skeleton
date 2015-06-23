<?php

namespace App\Contracts\Repositories;

interface PasswordResets
{
    public function create(\App\User $user);
}
