<?php

namespace App\Dto;

class RegisterDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $passwordConfirmation,
    ) {
    }
}
