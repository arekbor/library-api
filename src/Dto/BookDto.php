<?php

namespace App\Dto;

final class BookDto 
{
    public int $id;
    public string $title;
    public UserDto $user;
}