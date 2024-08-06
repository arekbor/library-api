<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserDto {
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\PasswordStrength]
    public string $password;
}