<?php

namespace App\Controller;

use App\Dto\AddBookDto;
use App\Entity\User;
use App\Service\BookService;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api', name: 'api_')]
final class BookController extends AbstractController
{
    private $bookService;
    private $logger;
    private $serializer;

    public function __construct(BookService $bookService, LoggerInterface $logger, SerializerInterface $serializer) 
    {
        $this->bookService = $bookService;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/books', name: 'add_book', methods: ['POST'])]
    public function add(#[MapRequestPayload] AddBookDto $dto, #[CurrentUser] User $user) : Response 
    {
        $this->bookService->add($dto, $user);
        
        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/books/{bookId}', name: 'remove_book', methods: ['DELETE'])]
    public function remove(int $bookId, #[CurrentUser] User $user) : Response 
    {
        $this->bookService->remove($bookId, $user);
        
        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/books/{page}/{limit}', name: 'list_books', methods: ['GET'])]
    public function list(int $page, int $limit, #[CurrentUser] User $user) 
    {
        $pagination = $this->bookService->paginateList($page, $limit, $user);
        $result = $this->serializer->serialize($pagination, 'json');

        return new Response($result);
    }
}