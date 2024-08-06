<?php

namespace App\Controller;

use App\Dto\AddBookDto;
use App\Entity\User;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api', name: 'api_')]
final class BookController extends AbstractController
{
    private $bookService;

    public function __construct(BookService $bookService) 
    {
        $this->bookService = $bookService;
    }

    #[Route('/books', name: 'add_book', methods: ['POST'])]
    public function register(#[MapRequestPayload] AddBookDto $dto, #[CurrentUser] User $user) : Response 
    {
        $this->bookService->add($dto, $user);
        
        return new Response(null, Response::HTTP_CREATED);
    }
}