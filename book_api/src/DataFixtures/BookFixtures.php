<?php

namespace App\DataFixtures;

use App\Component\MediaObject\MediaObjectFactory;
use App\Entity\Book;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Handler\UploadHandler;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    private const BOOKS = [
        [
            "name" => "Yashamoq",
            "description" => "Juda ta'sirli hikoya.",
            "text" => "Katta matn",
            "category" => "1",
            "image" => "/images/1.png"
        ],
        [
            "name" => "Kichkina Shahzoda",
            "description" => "Uchuvchi haqida.",
            "text" => "Kitob matni",
            "category" => "5",
            "image" => "/images/2.png"
        ],
        [
            "name" => "Naruto",
            "description" => "Nindzya klanlari haqida.",
            "text" => "Kitob matni",
            "category" => "4",
            "image" => "/images/3.jpg"
        ],
        [
            "name" => "Haikyuu",
            "description" => "Valeybol jamoasi  haqida.",
            "text" => "Kitob matni",
            "category" => "4",
            "image" => "/images/4.jpeg"
        ],
        [
            "name" => "Solo leveling",
            "description" => "Jin wooning yolgiz kotariishi.",
            "text" => "Kitob matni",
            "category" => "3",
            "image" => "/images/5.jpeg"
        ],
        [
            "name" => "Attack on titan",
            "description" => "Odamxo'r titanlarning paydo bo'lishi.",
            "text" => "Kitob matni",
            "category" => "3",
            "image" => "/images/6.png"
        ],
        [
            "name" => "Bleach",
            "description" => "Shinigamilar olami haqida.",
            "text" => "Kitob matni",
            "category" => "2",
            "image" => "/images/7.png"
        ],
        [
            "name" => "Demon slayer",
            "description" => "Iblislar olami haqida.",
            "text" => "Kitob matni",
            "category" => "5",
            "image" => "/images/8.png"
        ],
        [
            "name" => "Qilich san'ati onlayn",
            "description" => "MMO olami haqida.",
            "text" => "Kitob matni",
            "category" => "5",
            "image" => "/images/9.jpg"
        ],
        [
            "name" => "Re:zero",
            "description" => "Nastuki Subaru haqida.",
            "text" => "Kitob matni",
            "category" => "5",
            "image" => "/images/10.jpg"
        ]
    ];

    public function __construct(
        private readonly UploadHandler $uploadHandler,
        private readonly MediaObjectFactory $mediaObjectFactory,
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::BOOKS as $book) {
            $category = $this->categoryRepository->find($book["category"]);

            $mediaObject = $this->mediaObjectFactory->create(
                $book['image'],
                'image/' . explode(".", $book['image'])[1],
                '/public'
            );

            $this->uploadHandler->upload($mediaObject, 'file');

            $manager->persist($mediaObject);

            $newBook = new Book();
            $newBook->setName($book["name"]);
            $newBook->setDescription($book["description"]);
            $newBook->setText($book["text"]);
            $newBook->setCategory($category);
            $newBook->setImage($mediaObject);

            $manager->persist($newBook);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
