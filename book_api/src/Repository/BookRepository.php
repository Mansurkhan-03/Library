<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    /**
     * @return Book[] Returns an array of Book objects
     */
    public function findByExampleField($userId): array
    {
        return $this->createQueryBuilder('b')
            ->select(
                'b.id',
                'b.name',
                'bc.name AS categoryName',
                'bi.id AS imageId',
                'bi.filePath AS filePath',
                'u.email AS userEmail',
                'u.phone AS userPhone'
            )
            ->leftJoin('b.category', 'bc')
            ->innerJoin('b.image', 'bi')
            ->join(User::class, 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $userId)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBooksLikedByUsername($username, $limit, $offset): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.likes', 'l')
            ->andWhere('l.username = :val')
            ->setParameter('val', $username)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBookExample($text): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.name like :val')
            ->setParameter('val', '%' . $text . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
