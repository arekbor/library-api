<?php

namespace App\Service;

use App\Dto\AddBookDto;
use App\Dto\BookDto;
use App\Entity\Book;
use App\Entity\User;
use App\Exception\NotAllowedException;
use App\Exception\NotFoundException;
use App\Repository\BookRepository;
use AutoMapper\AutoMapper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class BookService 
{
    private $autoMapper;
    private $entityManager;
    private $bookRepository;
    private $paginator;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository, PaginatorInterface $paginator) 
    {
        $this->autoMapper = AutoMapper::create();
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
    }

    public function add(AddBookDto $dto, User $user) : void 
    {
        $book = $this->autoMapper->map($dto, Book::class);
        $book->setUser($user);
        $book->setRental(new \DateTime('now', new \DateTimeZone('UTC')));

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function remove(int $bookId, User $user) : void 
    {
        /**
         * @var Book|null $book
         */
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);

        if ($book === null) 
        {
            throw new NotFoundException();
        }

        if($book->getUser() !== $user) 
        {
            throw new NotAllowedException();
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function paginateList(int $page, int $limit, User $user) : PaginationInterface
    {
        $queryBuilder = $this->bookRepository->getQuery();
        $queryBuilder
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $user->getId());

        $items = $this->paginator->paginate($queryBuilder, $page, $limit);

        $newItems = [];
        foreach ($items->getItems() as $item) 
        {
            $newItems[] = $this->autoMapper->map($item, BookDto::class);
        }

        $items->setItems($newItems);

        return $items;
    }
}