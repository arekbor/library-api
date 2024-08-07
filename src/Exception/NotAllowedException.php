<?php

namespace App\Exception;

use Exception;

final class NotAllowedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not allowed');
    }
}