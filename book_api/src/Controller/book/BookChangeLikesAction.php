<?php

declare(strict_types=1);

namespace App\Controller\book;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookChangeLikesAction extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Book
    {
        $bookId = $request->query->get('id');
        $username = $request->query->get('username');
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $book = $entityManager->getRepository(Book::class)->findOneBy(['id' => $bookId]);

        if ($book->getLikes()->contains($user)) {
            $book->removeLike($user);
        } else {
            $book->addLike($user);
        }

        $entityManager->persist($book);
        $entityManager->flush();
        return $book;
    }
}