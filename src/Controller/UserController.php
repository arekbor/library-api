<?php

namespace App\Controller;

use App\Dto\RegisterUserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api', name: 'api_')]
final class UserController extends AbstractController {
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    #[Route('/users/register', name: 'user_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegisterUserDto $dto) : Response {
        $this->userService->register($dto);
        
        return new Response(null, Response::HTTP_CREATED);
    }
}