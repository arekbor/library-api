<?php

namespace App\Service;

use App\Dto\AddBookDto;
use App\Entity\Book;
use App\Entity\User;
use AutoMapper\AutoMapper;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

final class BookService 
{
    private $autoMapper;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->autoMapper = AutoMapper::create();
        $this->entityManager = $entityManager;
    }

    public function add(AddBookDto $dto, User $user) : void 
    {
        $book = $this->autoMapper->map($dto, Book::class);
        $book->setUser($user);
        $book->setRental(new \DateTime('now', new \DateTimeZone('UTC')));

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
}