<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class AddBookDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $title;
}