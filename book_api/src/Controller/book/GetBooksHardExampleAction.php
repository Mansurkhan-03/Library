<?php

declare(strict_types=1);

namespace App\Controller\book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetBooksHardExampleAction extends AbstractController
{
    public function __invoke(BookRepository $bookRepository): Book
    {
        return $bookRepository->findOneBookExample('titan');
    }
}
