<?php

declare(strict_types=1);

namespace App\Controller\book;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class GetBooksByLikeAction extends AbstractController
{
    public function __invoke(Request $request, BookRepository $bookRepository): array
    {
        $username = $request->query->get("username");
        $page = $request->query->get("page");

        if (!$username) {
            throw new BadRequestException('Username\'ni yuboring!');
        }

        return $bookRepository->findBooksLikedByUsername(['category' => $username], 10, --$page * 10);
    }
}