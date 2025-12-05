<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private const CATEGORIES = [
        ['name' => 'Badiiy adabiyot'],
        ['name' => 'Ilmiy-ommabop'],
        ['name' => 'Tarix'],
        ['name' => 'Fantastika'],
        ['name' => 'Diniy adabiyot'],
        ['name' => 'Bolalar uchun'],
        ['name' => 'Psixologiya'],
        ['name' => 'Biznes va motivatsiya'],
        ['name' => 'Siyosat'],
        ['name' => "O'quv qo'llanmalar"],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $category) {
            $newCategory = new Category();
            $newCategory->setName($category['name']);

            $manager->persist($newCategory);
        }

        $manager->flush();
    }
}
