<?php

declare(strict_types=1);

namespace App\Controller\book;

use App\Component\Book\BookInformationDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class BookInformationAction extends AbstractController
{
    public function __invoke(#[MapRequestPayload] BookInformationDto $informationDto): BookInformationDto
    {
        return $informationDto;
    }
}