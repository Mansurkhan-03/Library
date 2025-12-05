<?php

namespace App\DataFixtures;

use App\Component\User\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private const USERS = [
        [
            "username" => "John Doe",
            "email" => "john@example.com",
            "password" => "pass1234",
            "age" => 25,
            "gender" => "male",
            "phone" => "998901112233"
        ],
        [
            "username" => "Alice Smith",
            "email" => "alice@example.com",
            "password" => "abc5678",
            "age" => 22,
            "gender" => "female",
            "phone" => "998931234567"
        ],
        [
            "username" => "Mike Tyson",
            "email" => "mike@example.com",
            "password" => "passabcd",
            "age" => 30,
            "gender" => "male",
            "phone" => "998935678900"
        ],
        [
            "username" => "Sara Lee",
            "email" => "sara@example.com",
            "password" => "sara321",
            "age" => 27,
            "gender" => "female",
            "phone" => "998937770000"
        ],
        [
            "username" => "Tom Cruise",
            "email" => "tom@example.com",
            "password" => "tomtom22",
            "age" => 28,
            "gender" => "male",
            "phone" => "998932223344"
        ],
        [
            "username" => "Lisa Ray",
            "email" => "lisa@example.com",
            "password" => "lisa555",
            "age" => 24,
            "gender" => "female",
            "phone" => "998934445566"
        ],
        [
            "username" => "James Bond",
            "email" => "james@example.com",
            "password" => "jamie999",
            "age" => 35,
            "gender" => "male",
            "phone" => "998936666777"
        ],
        [
            "username" => "Emma Stone",
            "email" => "emma@example.com",
            "password" => "emma123",
            "age" => 26,
            "gender" => "female",
            "phone" => "998938888999"
        ],
        [
            "username" => "Daniel Craig",
            "email" => "daniel@example.com",
            "password" => "daniel88",
            "age" => 33,
            "gender" => "male",
            "phone" => "998930000111"
        ],
        [
            "username" => "Nina Dobrev",
            "email" => "nina@example.com",
            "password" => "nina777",
            "age" => 23,
            "gender" => "female",
            "phone" => "998931111222"
        ]
    ];


    public function __construct(private readonly UserFactory $factory)
    {}

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $user) {
            $newUser = $this->factory->create(
                $user['username'],
                $user['password'],
                $user['email'],
                $user['age'],
                $user['gender'],
                $user['phone']
            );
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}
