<?php

namespace App\Service;

use App\Dto\RegisterUserDto;
use App\Entity\User;
use AutoMapper\AutoMapper;
use Doctrine\ORM\EntityManagerInterface;

final class UserService {
    protected $autoMapper;
    protected $entityManager;
    protected $identityService;

    public function __construct(EntityManagerInterface $entityManager, IdentityService $identityService) {
        $this->autoMapper = AutoMapper::create();
        $this->entityManager = $entityManager;
        $this->identityService = $identityService;
    }

    public function register(RegisterUserDto $dto) : void {
        $user = $this->autoMapper->map($dto, User::class);

        $hash = $this->identityService->hashPassword($dto->password);
        $user->setPassword($hash);
        $user->setRoles(["IS_AUTHENTICATED_FULLY"]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}