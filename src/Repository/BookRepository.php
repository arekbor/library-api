<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository 
{
    public function __construct(ManagerRegistry $registry) 
    {
        parent::__construct($registry, Book::class);
    }

    public function getQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }
}
