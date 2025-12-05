<?php

declare(strict_types=1);

namespace App\Component\Book;

use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\Serializer\Annotation\Groups;

readonly class BookInformationDto
{

    public function __construct(
        #[Groups(["book:write"])]
        private string $givenName,

        #[Groups(["book:write", "book:read"])]
        private string $bookName,

        #[Groups(["book:write", "book:read"])]
        private string $categoryName,

        #[Groups(["book:write", "book:read"])]
        private string $descriptionn,

        #[Groups(["book:write", "book:read"])]
        private float $price
    )
    {}

    public function getGivenName(): string
    {
        return $this->givenName;
    }

    public function getBookName(): string
    {
        return $this->bookName;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getDescriptionn(): string
    {
        return $this->descriptionn;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
