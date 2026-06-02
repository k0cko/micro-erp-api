<?php

namespace App\Service\User;

interface PasswordHasher
{
    public function hash(string $password): string;
}