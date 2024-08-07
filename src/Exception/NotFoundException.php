<?php

namespace App\Exception;

use Exception;

final class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Entity not found');
    }
}