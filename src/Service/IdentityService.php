<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

final class IdentityService {
    private $passwordHasher;

    public function __construct() {
        $passwordHahserFactory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        $this->passwordHasher = $passwordHahserFactory->getPasswordHasher('common');
    }

    public function hashPassword(string $password) : string {
        return $this->passwordHasher->hash($password);
    }
}